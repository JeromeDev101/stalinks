<?php

namespace App\Models;

use App\Repositories\Traits\Loggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\Formula;
use App\Models\Registration;

class Publisher extends Model
{
    use SoftDeletes, Loggable;

    protected $guarded = [];
    protected $table = 'publisher';
    protected $appends = [
        'custom_created',
        'custom_updated',
        'custom_url',
        'custom_continent',
        'custom_price',
        'custom_username',
        'custom_topic',
        'custom_new_price',
        'custom_new_prices'
    ];

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
            'is_https'
        ];

    public function getCustomUrlAttribute() {
        return $this->remove_http($this->url);
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

    public function getCustomPriceAttribute() {
        return isset($this->price) ? $this->price:0;
    }

    public function getCustomCreatedAttribute() {
        return Carbon::parse($this->created_at)->format('Y-m-d');
    }

    public function getCustomUpdatedAttribute() {
        return Carbon::parse($this->updated_at)->format('Y-m-d');
    }

    public function getCustomNewPricesAttribute() {
        return $this->price;
        // return $this->computePriceStalinks($this->price, $this->inc_article);
    }

    public function getCustomNewPriceAttribute() {
        return $this->price;
        // return $this->computePrice($this->price, $this->inc_article);
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

    private function remove_http($url) {
        $disallowed = array('http://', 'https://', 'www.');
        foreach($disallowed as $d) {
           if(strpos($url, $d) === 0) {
              return str_replace($d, '', $url);
           }
        }
        return $url;
    }

    private function percentage($percent, $total) {
        return number_format(($percent/ 100) * $total, 2, '.', '');
    }

    private function computePrice($price, $article) {

        $formula = Formula::first();

        $activeUser = Auth::user();
        $registration = Registration::where('email', $activeUser->email)->first();
        $selling_price = $price;

        $percent = floatval($formula->percentage);
        $additional = floatval($formula->additional);

        if( isset($registration) ){

            $type = $registration->type;
            $commission = strtolower($registration->commission);

            if( $price != '' && $price != null ){

                if( $type == 'Buyer' ){

                    if( strtolower($article) == 'yes' ){

                        if( $commission == 'no' ){
                            $selling_price = $price;
                        }

                        if( $commission == 'yes' ){
                            $percentage = $this->percentage($percent, $price);
                            $selling_price = floatval($percentage) + floatval($price);
                        }
                    }

                    if( strtolower($article) == 'no' ){

                        if( $commission == 'no' ){
                            $selling_price = floatval($price) + $additional;
                        }

                        if( $commission == 'yes' ){
                            $percentage = $this->percentage($percent, $price);
                            $selling_price = floatval($percentage) + floatval($price) + $additional;
                        }

                    }
                }

            }
        }

        $selling_price = round(floatval($selling_price));
//        $selling_price = floatval($selling_price);

        return $selling_price;
    }


    private function computePriceStalinks($price, $article) {

        $formula = Formula::first();
        $selling_price = $price;
        $percent = floatval($formula->percentage);
        $additional = floatval($formula->additional);
        $commission = 'yes';

        if( $price != '' && $price != null ){

            if( strtolower($article) == 'yes' ){

                if( $commission == 'no' ){
                    $selling_price = $price;
                }

                if( $commission == 'yes' ){
                    $percentage = $this->percentage($percent, $price);
                    $selling_price = floatval($percentage) + floatval($price);
                }
            }

            if( strtolower($article) == 'no' ){

                if( $commission == 'no' ){
                    $selling_price = floatval($price) + $additional;
                }

                if( $commission == 'yes' ){
                    $percentage = $this->percentage($percent, $price);
                    $selling_price = floatval($percentage) + floatval($price) + $additional;
                }

            }

        }

        $selling_price = round(floatval($selling_price));

        return $selling_price;
    }
}
