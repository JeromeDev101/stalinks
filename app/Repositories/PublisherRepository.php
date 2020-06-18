<?php

namespace App\Repositories;

use App\Repositories\Contracts\PublisherRepositoryInterface;
use App\Repositories\BaseRepository;
use App\Models\ExtDomain;
use App\Models\Publisher;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PublisherRepository extends BaseRepository implements PublisherRepositoryInterface {
    protected $extDomain;

    public function __construct(ExtDomain $model)
    {
        parent::__construct($model);
    }

    public function getList($filter)
    {
        $user = Auth::user();
        $list = DB::table('publisher')
                ->select('publisher.*','users.name', 'users.isOurs', 'registration.company_name', 'countries.name AS language')
                ->leftJoin('users', 'publisher.user_id', '=', 'users.id')
                ->leftJoin('registration', 'users.email', '=', 'registration.email')
                ->leftJoin('countries', 'publisher.language_id', '=', 'countries.id');

        if( $user->isOurs != 0 && $user->type != 10 ){
            $list = $list->where('users.id', $user->id);
        }

        if( isset($filter['search']) && !empty($filter['search']) ){
            $list = $list->where('registration.company_name', 'like', '%'.$filter['search'].'%')
                    ->orWhere('users.name', 'like', '%'.$filter['search'].'%');
        }

        if( isset($filter['language_id']) && !empty($filter['language_id']) ){
            $list = $list->where('language_id', $filter['language_id']);
        }

        return [
            "data" => $list->get(),
            "total" => $list->count()
        ];
    }

    public function importExcel($file){
        $id = Auth::user()->id;
        $csv = fopen($file, 'r');
        $ctr = 0;
        while ( ($line = fgetcsv($csv) ) !== FALSE) {

            if( $ctr > 0 ){
                $url = $line[0];
                $ur = $line[1];
                $dr = $line[2];
                $backlinks = $line[3];
                $ref_domain = $line[4];
                $org_keywords = $line[5];
                $org_traffic = $line[6];
                $price = $line[7];

                Publisher::create([
                    'user_id' => $id,
                    'url' => $url,
                    'ur' => $ur,
                    'dr' => $dr,
                    'backlinks' => $backlinks,
                    'ref_domain' => $ref_domain,
                    'org_keywords' => $org_keywords,
                    'org_traffic' => $org_traffic,
                    'price' => preg_replace('/[^0-9.\-]/', '', $price),
                ]);
            }
                
            $ctr++;
        }

        fclose($csv);

        return true;
    }
}