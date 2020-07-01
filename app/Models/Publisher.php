<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Publisher extends Model
{
    protected $guarded = [];
    protected $table = 'publisher';

    public function user() {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    public function country() {
        return $this->belongsTo('App\Models\Country', 'language_id');
    }

    public function buyer_purchased() {
        return $this->belongsToMany('App\Models\BuyerPurchased', 'publisher_id');
    }
}
