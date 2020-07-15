<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pricelist extends Model
{
    protected $table = 'price_list';

    protected $fillable = [ 'code', 'price' ];
}
