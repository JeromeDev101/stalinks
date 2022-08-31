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
        'address_value',
        'account_name',
        'account_iban',
        'bank_name',
        'swift_code',
        'beneficiary_add',
        'account_holder',
        'account_type',
        'routing_num',
        'wire_routing_num'
    ];

    public function payment_type_image() {
        return $this->hasMany('App\PaymentTypeImage');
    }
}
