<?php

namespace App\Repositories;

use App\Repositories\Contracts\PublisherRepositoryInterface;
use App\Repositories\BaseRepository;
use App\Models\ExtDomain;
use App\Models\Publisher;
use Illuminate\Database\Eloquent\Model;

class PublisherRepository extends BaseRepository implements PublisherRepositoryInterface {
    protected $extDomain;

    public function __construct(ExtDomain $model)
    {
        parent::__construct($model);
    }

    public function getList()
    {
        $list = Publisher::get();
        return [
            "data" => $list,
            "total" => $list->count()
        ];
    }

    public function importExcel($file){
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
                    'user_id' => 1,
                    'url' => $url,
                    'ur' => $ur,
                    'dr' => $dr,
                    'backlinks' => $backlinks,
                    'ref_domain' => $ref_domain,
                    'org_keywords' => $org_keywords,
                    'org_traffic' => $org_traffic,
                    'price' => $price,
                ]);
            }
                
            $ctr++;
        }

        fclose($csv);

        return true;
    }
}