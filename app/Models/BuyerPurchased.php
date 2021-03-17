<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BuyerPurchased extends Model
{
    protected $guarded = [];
    protected $table = 'buyer_purchased';

    protected $fillable = [
        'user_id_buyer',
        'publisher_id',
        'status',
    ];

    public function publisher() {
        return $this->hasMany('App\Models\Publisher', 'id', 'publisher_id');
    }

}
