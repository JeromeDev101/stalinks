<?php

namespace App\Repositories;

use App\Repositories\Contracts\PublisherRepositoryInterface;
use App\Repositories\BaseRepository;
use App\Models\ExtDomain;
use App\Models\Publisher;
use App\Models\Registration;
use App\Libs\Ahref;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PublisherRepository extends BaseRepository implements PublisherRepositoryInterface {
    protected $extDomain;

    public function __construct(Publisher $model)
    {
        parent::__construct($model);
    }

    public function getList($filter)
    {
        $user = Auth::user();
        $paginate = (isset($filter['paginate']) && !empty($filter['paginate']) ) ? $filter['paginate']:25;
        $list = Publisher::select('publisher.*','registration.username','users.name', 'users.username as user_name', 'users.isOurs', 'registration.company_name', 'countries.name AS country_name')
                ->leftJoin('users', 'publisher.user_id', '=', 'users.id')
                ->leftJoin('registration', 'users.email', '=', 'registration.email')
                ->leftJoin('countries', 'publisher.language_id', '=', 'countries.id')
                ->orderBy('created_at', 'desc');

        $registered = Registration::where('email', $user->email)->first();

        if( $user->type != 10 && isset($registered->type) && $registered->type == 'Seller' ){
            $list->where('publisher.user_id', $user->id);
        }

        if( isset($filter['seller']) && !empty($filter['seller']) ){
            $list->where('publisher.user_id', $filter['seller']);
        }

        if( $user->isOurs != 0 && $user->type != 10 ){
            $list = $list->where('users.id', $user->id);
        }

        if( isset($filter['search']) && !empty($filter['search']) ){
            $list = $list->where('publisher.url', 'like', '%'.$filter['search'].'%');
        }

        if( isset($filter['got_ahref']) && !empty($filter['got_ahref']) ){
            if( $filter['got_ahref'] == 'Without' ){
                $list = $list->where('publisher.ur', 0)
                ->where('publisher.dr', 0)
                ->where('publisher.backlinks', 0)
                ->where('publisher.ref_domain', 0)
                ->where('publisher.org_keywords', 0)
                ->where('publisher.org_traffic', 0);
            }

            if( $filter['got_ahref'] == 'With' ){
                $list = $list->where('publisher.ur', '!=', '0')
                ->where('publisher.dr', '!=', '0')
                ->where('publisher.backlinks', '!=', '0')
                ->where('publisher.ref_domain', '!=', '0');
            }
        }

        if( isset($filter['date']) && !empty($filter['date']) ){
            $list = $list->where('publisher.updated_at', 'like', '%'.$filter['date'].'%');
        }

        if( isset($filter['language_id']) && !empty($filter['language_id']) ){
            $list = $list->where('publisher.language_id', $filter['language_id']);
        }

        if( isset($filter['inc_article']) && !empty($filter['inc_article']) ){
            $list = $list->where('publisher.inc_article', $filter['inc_article']);
        }

        if( isset($filter['valid']) && !empty($filter['valid']) ){
            if( is_array($filter['valid']) ){
                $list = $list->whereIn('publisher.valid', $filter['valid']);
            }else{
                $list = $list->where('publisher.valid', $filter['valid']);
            }
        }


        if( isset($filter['paginate']) && !empty($filter['paginate']) && $filter['paginate'] == 'All' ){
            return [
                "data" => $list->get(),
                "total" => $list->count()
            ];
        }else{
            return $list->paginate($paginate);
        }
            
    }

    public function importExcel($file){
        $language = $file['language'];
        $csv_file = $file['file'];

        $result = true;
        $message = '';
        $file_message = '';

        $id = Auth::user()->id;
        $csv = fopen($csv_file, 'r');
        $ctr = 0;
        while ( ($line = fgetcsv($csv) ) !== FALSE) {
            if(count($line) > 3 || count($line) < 3){
                $message = "Please check the header: Url, Price and Inc Article only.";
                $file_message = "Invalid Header format. ".$message;
                $result = false;
                break;
            }

            if( $ctr > 0 ){
                $url = $line[0];
                $price = $line[1];
                $article = $line[2];

                if( trim($url, " ") != '' ){
                    Publisher::create([
                        'user_id' => $id,
                        'language_id' => $language,
                        'url' => $url,
                        'ur' => 0,
                        'dr' => 0,
                        'backlinks' => 0,
                        'ref_domain' => 0,
                        'org_keywords' => 0,
                        'org_traffic' => 0,
                        'price' => preg_replace('/[^0-9.\-]/', '', $price),
                        'inc_article' => ucwords( strtolower( trim($article, " ") ) ),
                        'valid' => 'unchecked',
                    ]);
                }
            }

            $ctr++;
        }

        fclose($csv);

        return [
            "success" => $result,
            "message" => $message,
            "errors" => [
                "file" => $file_message,
            ],
        ];
    }

    /**
     * Get ahrefs with promise for request async concurrency
     * @param array $listIds
     * @param array $configs
     * @return array
     */
    public function getAhrefs($listIds, $configs)
    {
        $publishers = Publisher::whereIn('id', $listIds)->get();

        if ($publishers->count() == 0) {
            return [];
        }

        $ahrefsInstant = new Ahref($configs);
        $data = $ahrefsInstant->getAhrefsPublisherAsync($publishers);
        return $data;
    }

    /**
     * get summary publisher list
     *
     */

     public function getPublisherSummary($user_id)
     {
         $publishers = $this->model->selectRaw('language_id, count(DISTINCT(id)) as total')
                            ->where('user_id', $user_id)
                            ->with(['country' => function($query) {
                                $query->select('id', 'name', 'code');
                            }])
                            ->groupBy('language_id')
                            ->distinct()
                            ->get();

        $dataReturn = [];
        $sumTotal = 0;

        foreach($publishers as $item) {
            $sumTotal += $item->total;
        }

        return [
            'total' => $sumTotal,
            'data' => $publishers
        ];
     }

     private function initArrayReport(&$dataReturn, $key, $country) {
        $dataReturn[$key] = ['country' => $country];
        foreach($this->statusList as $value) {
            $dataReturn[$key][$value] = 0;
        }
    }
}