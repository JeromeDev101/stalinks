<?php

namespace App\Jobs;

use App\Models\Country;
use App\Models\CsvUploadLog;
use App\Models\Language;
use App\Models\Publisher;
use App\Notifications\CsvUploaded;
use App\Repositories\PublisherRepository;
use App\Repositories\Traits\NotificationTrait;
use App\Services\HttpClient;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Validator;

class UploadCsvListPublisher implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, NotificationTrait;

    protected $log;
    protected $user;
    protected $records;
    protected $publisher;
    protected $httpClient;

    /**
     * Create a new job instance.
     *
     * @param $records
     * @param $user
     * @param $log
     */
    public function __construct($records, $user, $log)
    {
        $this->log = $log;
        $this->user = $user;
        $this->records = $records;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->startPublisherCsvUpload($this->user->id);

        $rows = [];
        $valid = [];
        $invalid = [];
        $message = '';
        $result = true;
        $valid_count = 0;
        $invalid_count = 0;

        $country_name_list = Country::pluck('name')->toArray();
        $language_name_list = Language::pluck('name')->toArray();

        $this->httpClient = new HttpClient();
        $this->publisher = new PublisherRepository();

        $headers = $this->user->isOurs == 1
            ? [
                'URL',
                'Price',
                'Inc Article',
                'Accept C&B',
                'KW Anchor',
                'Language',
                'Topic',
                'Country',
            ]
            : [
                'URL',
                'Price',
                'Inc Article',
                'Seller ID',
                'Accept C&B',
                'Language',
                'Topic',
                'Country',
                'KW Anchor'
            ];

        // if records is empty, stop the upload, throw error
        if (count($this->records) <= 1) {
            $message = "Invalid file. Uploaded csv file is empty.";
            $result = false;
        } else {
            // get field names from header column
            $fields = $this->records[0];

            // if fields are invalid, throw error
            if ($headers !== $fields) {
                $message = "Invalid file. Headers must be: " . implode(', ', $headers) . " only";
                $result = false;
            } else {
                // remove the header column
                array_shift($this->records);

                // check number of lines, must not exceed 500, if invalid throw error
                if (count($this->records) > 200) {
                    $message = "Invalid file: Please upload only 200 urls at a time. Number of urls in file: "
                        . count($this->records);

                    $result = false;
                } else {

                    // generate item array
                    foreach ($this->records as $index => $record) {
                        $record = array_combine($fields, $record);

                        $url = trim_special_characters($record['URL']);
                        $price = trim_special_characters($record['Price']);
                        $article = trim_special_characters($record['Inc Article']);
                        $seller_id = $this->user->isOurs == 1
                            ? $this->user->id
                            : trim_special_characters($record['Seller ID']);
                        $accept = trim_special_characters($record['Accept C&B']);
                        $language_excel = trim_special_characters($record['Language']);
                        $topic = trim_special_characters($record['Topic']);
                        $country = trim_special_characters($record['Country']);
                        $kw_anchor = trim_special_characters($record['KW Anchor']);

                        // remove http
                        $url = remove_http($url);

                        // remove space
                        $url = trim($url, " ");

                        // remove /
                        $url = rtrim($url,"/");

                        if($url == ''
                            || $price == ''
                            || $topic == ''
                            || $accept == ''
                            || $article == ''
                            || $country == ''
                            || $kw_anchor == ''
                            || $seller_id == ''
                            || $language_excel == '') {

                            $message = "Invalid data. Columns cannot be empty. Check in row " . (intval($index) + 1);
                            $result = true;
                            $invalid_count++;

                            array_push($invalid, $message);

                        } else {
                            // check language
                            if (!in_array($language_excel, $language_name_list)) {
                                $message = "No language name of " . $language_excel . ". Check in row " . (intval($index) + 1);
                                $result = true;
                                $invalid_count++;

                                array_push($invalid, $message);
                            } else {

                                // check country
                                if (!in_array($country, $country_name_list)) {
                                    $message = "No country name of " . $country . ". Check in row " . (intval($index) + 1);
                                    $result = true;
                                    $invalid_count++;

                                    array_push($invalid, $message);
                                } else {
                                    $lang = $this->publisher->getLanguage($language_excel);
                                    $count = $this->publisher->getCountry($country);

                                    $row = [
                                        'user_id'      => $seller_id,
                                        'language_id'  => $lang,
                                        'continent_id' => $count->continent_id,
                                        'country_id'   => $count->id,
                                        'url'          => $url,
                                        'ur'           => 0,
                                        'dr'           => 0,
                                        'backlinks'    => 0,
                                        'ref_domain'   => 0,
                                        'org_keywords' => 0,
                                        'org_traffic'  => 0,
                                        'price'        => preg_replace('/[^0-9.\-]/', '', $price),
                                        'inc_article'  => ucwords(strtolower(trim($article, " "))),
                                        'valid'        => 'unchecked',
                                        'casino_sites' => ucwords(strtolower(trim($accept, " "))),
                                        'topic'        => $topic,
                                        'kw_anchor'    => $kw_anchor,
                                        'is_https'     => $this->httpClient->getProtocol($url) == 'https' ? 'yes' : 'no',
                                        'from'         => 'csv',
                                    ];

                                    if (count($rows)) {
                                        // check for duplicate seller and url in rows array
                                        $existing_rows = array_filter($rows, function ($var) use ($row) {
                                            return ($var['user_id'] == $row['user_id'] && $var['url'] == $row['url']);
                                        });

                                        if (!count($existing_rows)) {
                                            $rows[$index] = $row;
                                        } else {
                                            $message = $this->user->isOurs == 1
                                                ? "Duplicate url in csv file. Check in row " . (intval($index) + 2)
                                                : "Duplicate seller and url in csv file. Check in row " . (intval($index) + 2);

                                            $invalid_count++;

                                            array_push($invalid, $message);
                                        }
                                    } else {
                                        $rows[$index] = $row;
                                    }
                                }
                            }
                        }
                    }

                    if (count($rows)) {

                        $validator = Validator::make(
                            $rows,
                            $this->publisher->generateValidationRules($rows),
                            $this->publisher->generateValidationMessages($rows, $this->user)
                        );

                        if ($validator->fails()) {

                            foreach ($validator->errors()->all() as $error) {
                                array_push($invalid, $error);
                            }
                        }

                        // insert valid
                        if (count($validator->valid())) {

                            $for_insert = array_chunk($validator->valid(), 100);

                            // chunk items
                            foreach ($for_insert as $insert_items_array) {
                                foreach ($insert_items_array as $insert) {
                                    Publisher::create($insert);

                                    array_push($valid, $insert['url']);
                                }
                            }
                        }

                        // count valid and invalid
                        $valid_count = count($validator->valid());
                        $invalid_count = $invalid_count + count($validator->invalid());
                    }
                }
            }
        }

        $this->log->update([
            'status' => $result,
            'valid' => count($valid) ? json_encode($valid) : null,
            'invalid' => count($invalid) ? json_encode($invalid) : null,
            'valid_count' => $valid_count,
            'invalid_count' => $invalid_count,
            'message' => $message,
            'end' => Carbon::now(),
        ]);

        $this->endPublisherCsvUpload($this->user->id);

        if ($this->user->email) {
            $this->user->notify(new CsvUploaded($this->user));
        }
    }

    public function fail($exception = null)
    {
        $log = CsvUploadLog::where('user_id', $this->user->id)->orderBy('id', 'DESC')->first();

        if ($log) {
            $log->update([
                'status' => 0,
                'message' => 'There were some errors while uploading the csv.',
                'end' => Carbon::now(),
            ]);
        }

        $this->endPublisherCsvUpload($this->user->id);

        \Log::error($exception);
    }
}
