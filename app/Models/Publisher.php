<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Publisher extends Model
{
    use SoftDeletes;

    protected $guarded = [];
    protected $table = 'publisher';

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
            'href_fetched_at'
        ];

    public function user() {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    public function country() {
        return $this->belongsTo('App\Models\Country', 'country_id');
    }

    public function buyer_purchased() {
        return $this->hasMany('App\Models\BuyerPurchased', 'publisher_id', 'id');
    }

    public function backlinks() {
        return $this->hasMany('App\Models\Backlink', 'publisher_id', 'id');
    }
}
