<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UsersPaymentType extends Model
{

    protected $table = 'users_payment_type';
    protected $guarded = [];

    public function paymentType() {
        return $this->belongsTo('App\Models\PaymentType', 'payment_id');
    }
}