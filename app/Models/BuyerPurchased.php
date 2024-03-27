<?php

namespace App\Models;

use App\Repositories\Traits\Loggable;
use Illuminate\Database\Eloquent\Model;

class BuyerPurchased extends Model
{
    use Loggable;

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

    public function buyer() {
        return $this->hasMany('App\Models\User', 'id', 'user_id_buyer');
    }

}
