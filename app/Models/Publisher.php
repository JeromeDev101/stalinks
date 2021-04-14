<?php

namespace App\Models;

use App\Repositories\Traits\Loggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

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
        'custom_topic'
    ];

    protected $fillable
        = [
            'user_id',
            'url',
            'language_id',
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
            'deleted_at'
        ];

    public function getCustomUrlAttribute() {
        return $this->remove_http($this->url);
    }

    public function getCustomUsernameAttribute() {
        return isset($this->user->username) ? $this->user->username:null;
    }

    public function getCustomTopicAttribute() {
        return isset($this->topic) ? $this->topic:null;
    }

    public function getCustomContinentAttribute() {
        return isset($this->continent->name) ? $this->continent->name:null;
    }

    public function getCustomPriceAttribute() {
        return isset($this->price) ? $this->price:null;
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

    private function remove_http($url) {
        $disallowed = array('http://', 'https://', 'www.');
        foreach($disallowed as $d) {
           if(strpos($url, $d) === 0) {
              return str_replace($d, '', $url);
           }
        }
        return $url;
    }
}
