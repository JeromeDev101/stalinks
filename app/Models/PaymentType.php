<?php

namespace App\Models;

use App\Repositories\Traits\Loggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentType extends Model
{
    use SoftDeletes, Loggable;

    protected $table = 'payment_type';
    protected $guarded = [];

    protected $fillable = [
        'type',
        'show_registration',
        'receive_payment',
        'send_payment',
        'account_value',
        'email_value',
        'address_value'
    ];

    public function payment_type_image() {
        return $this->hasMany('App\PaymentTypeImage');
    }
}
