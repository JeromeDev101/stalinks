<?php

namespace App\Repositories;

use App\Repositories\Contracts\PublisherRepositoryInterface;
use App\Repositories\BaseRepository;
use App\Models\ExtDomain;
use App\Models\Publisher;
use App\Models\Registration;
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
                ->select('publisher.*','users.name', 'users.isOurs', 'registration.company_name', 'countries.name AS country_name')
                ->leftJoin('users', 'publisher.user_id', '=', 'users.id')
                ->leftJoin('registration', 'users.email', '=', 'registration.email')
                ->leftJoin('countries', 'publisher.language_id', '=', 'countries.id')
                ->where('deleted_at', '=', '')
                ->orWhereNull('deleted_at');

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

        // return $list->paginate(5);

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
                $language = $line[0];
                $url = $line[1];
                $ur = $line[2];
                $dr = $line[3];
                $backlinks = $line[4];
                $ref_domain = $line[5];
                $org_keywords = $line[6];
                $org_traffic = $line[7];
                $price = $line[8];
                $article = $line[9];

                if( trim($url, " ") != '' ){
                    Publisher::create([
                        'user_id' => $id,
                        'language' => $language,
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
}