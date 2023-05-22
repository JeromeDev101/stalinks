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
}
