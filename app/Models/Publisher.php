<?php

namespace App\Models;

use Carbon\Carbon;
use App\Models\Formula;
use App\Models\Registration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Repositories\Traits\Loggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Publisher extends Model
{
    use SoftDeletes, Loggable;

    private static $formulaData = null;
    protected $guarded = [];
    protected $table = 'publisher';

//    protected $appends = [
//        'custom_created',
//        'custom_updated',
//        'custom_url',
//        'custom_continent',
//        'custom_username',
//        'custom_topic'
//    ];

    protected $fillable
        = [
            'user_id',
            'url',
            'language_id',
            'country_id',
            'code_comb',
            'price_basis',
            'code_price',
            'continent_id',
            'price',
            'inc_article',
            'casino_sites',
            'topic',
            'kw_anchor',
            'qc_validation',
            'valid',
            'href_fetched_at',
            'deleted_at',
            'is_https',
            'ur',
            'dr',
            'backlinks',
            'ref_domain',
            'org_keywords',
            'org_traffic',
            'direct_admin_sites',
            'anchor_text',
            'link',
            'from',
        ];

    public function getCustomUrlAttribute() {
        return remove_http($this->url);
    }

    public function getCustomUsernameAttribute() {
        return isset($this->user->username) ? $this->user->username:'N/A';
    }

    public function getCustomTopicAttribute() {
        return isset($this->topic) && $this->topic != '' ? $this->topic:'N/A';
    }

    public function getCustomContinentAttribute() {
        return isset($this->continent->name) ? $this->continent->name:'N/A';
    }

    public function getCustomCreatedAttribute() {
        return Carbon::parse($this->created_at)->format('Y-m-d');
    }

    public function getCustomUpdatedAttribute() {
        return Carbon::parse($this->updated_at)->format('Y-m-d');
    }

    public function user() {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    public function country() {
        return $this->belongsTo('App\Models\Country', 'country_id');
    }

    public function continent() {
        return $this->belongsTo('App\Models\Continent', 'continent_id');
    }

    public function buyer_purchased() {
        return $this->hasMany('App\Models\BuyerPurchased', 'publisher_id', 'id');
    }

    public function backlinks() {
        return $this->hasMany('App\Models\Backlink', 'publisher_id', 'id');
    }

    public function backlinks_interested() {
        return $this->hasMany('App\Models\BacklinksInterested', 'publisher_id', 'id');
    }

    // public function getComputedPriceAttribute() {
    //     $price = $this->attributes['price'];
    //     $article = $this->attributes['inc_article'];

    //     $formulaData = $this->getFormulaData();

    //     $sellingPrice = $price;
    //     $percent = $formulaData->percentage;
    //     $additional = $formulaData->additional;
    //     $commission = 'yes';
    //     $buyer_type_basic = $formulaData->basic;
    //     $buyer_type_medium = $formulaData->medium;
    //     $buyer_type_premium = $formulaData->premium;
    //     $type = null;
    //     $buyer_type = null;

    //     $registration = Auth::user()->registration;

    //     if ($registration) {
    //         $type = $registration->type;
    //         $buyer_type = $registration->buyer_type;
    //         $commission = strtolower($registration->commission);

    //         if ($buyer_type == 'Basic') {
    //             $percent = $buyer_type_basic;
    //         } else if ($buyer_type == 'Medium') {
    //             $percent = $buyer_type_medium;
    //         } else {
    //             $percent = $buyer_type_premium;
    //         }

    //         if (!empty($price)) {
    //             if ($type == 'Buyer') {
    //                 if ($article) {
    //                     if (strtolower($article) == 'yes') {
    //                         if ($commission == 'no') {
    //                             $sellingPrice = $price;
    //                         }

    //                         if ($commission == 'yes') {
    //                             $percentage = $this->percentage($percent, $price);
    //                             $sellingPrice = $percentage + $price;
    //                         }
    //                     }

    //                     if (strtolower($article) == 'no') {
    //                         if ($commission == 'no') {
    //                             $sellingPrice = $price + $additional;
    //                         }

    //                         if ($commission == 'yes') {
    //                             $percentage = $this->percentage($percent, $price);
    //                             $sellingPrice = $percentage + $price + $additional;
    //                         }
    //                     }
    //                 }
    //             }
    //         }
    //     }

    //     return round($sellingPrice);
    // }

    // public function getComputedPriceStalinksAttribute() {
    //     $price = $this->attributes['price'];
    //     $article = $this->attributes['inc_article'];

    //     $sellingPrice = $price;
    //     $percent = null;
    //     $additional = null;
    //     $commission = 'yes';

    //     if (!empty($price)) { // check if price has a value
    //         if ($article) {
    //             if (strtolower($article) == 'yes') { // check if with article
    //                 if ($commission == 'yes') {
    //                     $formulaData = $this->getFormulaData();
    //                     if ($formulaData) {
    //                         $percent = $formulaData->percentage;
    //                         $additional = $formulaData->additional;
    //                         $percentage = $this->percentage($percent, $price);
    //                         $sellingPrice = $percentage + $price;
    //                     }
    //                 }
    //             }

    //             if (strtolower($article) == 'no') { // check if without article
    //                 if ($commission == 'yes') {
    //                     $formulaData = $this->getFormulaData();
    //                     if ($formulaData) {
    //                         $percent = $formulaData->percentage;
    //                         $additional = $formulaData->additional;
    //                         $percentage = $this->percentage($percent, $price);
    //                         $sellingPrice = $percentage + $price + $additional;
    //                     }
    //                 }
    //             }
    //         }
    //     }

    //     return round($sellingPrice);
    // }

    // protected function percentage($percent, $total) {
    //     return number_format(($percent / 100) * $total, 2);
    // }

    protected function getFormulaData()
    {
        if (self::$formulaData === null) {
            self::$formulaData = DB::table('formula')->first();
        }

        return self::$formulaData;
    }
}
