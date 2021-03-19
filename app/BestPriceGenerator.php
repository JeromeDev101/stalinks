<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BestPriceGenerator extends Model
{
    public $timestamps = true;

    protected $table = 'best_price_generation_log';

    protected $fillable
        = [
            'user_id',
            'status'
        ];
}
