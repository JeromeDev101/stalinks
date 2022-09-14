<?php

namespace App\Models;

use App\Repositories\Traits\Loggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UsersPaymentType extends Model
{
    use Loggable, SoftDeletes;

    protected $table = 'users_payment_type';
    protected $guarded = [];

    public function user() {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    public function paymentType() {
        return $this->belongsTo('App\Models\PaymentType', 'payment_id');
    }
}
