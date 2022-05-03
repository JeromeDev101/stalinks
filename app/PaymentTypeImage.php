<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PaymentTypeImage extends Model
{
    protected $table = 'payment_type_image';

    protected $fillable = [
        'payment_type_id',
        'path',
        'image_type'
    ];
  
    public function payment_type()
    {
        return $this->belongsTo('App\Models\PaymentType', 'payment_type_id');
    }
}
