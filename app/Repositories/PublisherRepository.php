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
        $list = Publisher::select('publisher.*','registration.username','users.name', 'users.isOurs', 'registration.company_name', 'countries.name AS country_name')
                ->leftJoin('users', 'publisher.user_id', '=', 'users.id')
                ->leftJoin('registration', 'users.email', '=', 'registration.email')
                ->leftJoin('countries', 'publisher.language_id', '=', 'countries.id')
                ->orderBy('created_at', 'desc');

        $registered = Registration::where('email', $user->email)->first();

        if( $user->type != 10 && $registered->type == 'Seller' ){
            $list->where('user_id', $user->id);
        }

        if( $user->isOurs != 0 && $user->type != 10 ){
            $list = $list->where('users.id', $user->id);
        }

        if( isset($filter['search']) && !empty($filter['search']) ){
            $list = $list->where('registration.company_name', 'like', '%'.$filter['search'].'%')
                    ->orWhere('users.name', 'like', '%'.$filter['search'].'%');
        }

        if( isset($filter['language_id']) && !empty($filter['language_id']) ){
            $list = $list->where('publisher.language_id', $filter['language_id']);
        }

        if( isset($filter['inc_article']) && !empty($filter['inc_article']) ){
            $list = $list->where('publisher.inc_article', $filter['inc_article']);
        }

        // return $list->paginate(10);

        return [
            "data" => $list->get(),
            "total" => $list->count()
        ];
    }

    public function importExcel($file){
        $language = $file['language'];
        $csv_file = $file['file'];

        $id = Auth::user()->id;
        $csv = fopen($csv_file, 'r');
        $ctr = 0;
        while ( ($line = fgetcsv($csv) ) !== FALSE) {

            if( $ctr > 0 ){
                $url = $line[0];
                $price = $line[1];
                $article = $line[2];
                $ur = $line[3];
                $dr = $line[4];
                $backlinks = $line[5];
                $ref_domain = $line[6];
                $org_keywords = $line[7];
                $org_traffic = $line[8];

                if( trim($url, " ") != '' ){
                    Publisher::create([
                        'user_id' => $id,
                        'language_id' => $language,
                        'url' => $url,
                        'ur' => $ur,
                        'dr' => $dr,
                        'backlinks' => $backlinks,
                        'ref_domain' => $ref_domain,
                        'org_keywords' => $org_keywords,
                        'org_traffic' => $org_traffic,
                        'price' => preg_replace('/[^0-9.\-]/', '', $price),
                        'inc_article' => ucwords( strtolower( trim($article, " ") ) )
                    ]);
                }

            }

            $ctr++;
        }

        fclose($csv);

        return true;
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