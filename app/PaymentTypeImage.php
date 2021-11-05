<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PaymentTypeImage extends Model
{
    protected $table = 'payment_type_image';

    public function payment_type()
    {
        return $this->belongsTo('App\Models\PaymentType', 'payment_type_id');
    }
}
