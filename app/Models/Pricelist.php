<?php

namespace App\Models;

use App\Repositories\Traits\Loggable;
use Illuminate\Database\Eloquent\Model;

class Pricelist extends Model
{
    use Loggable;
    
    protected $table = 'price_list';

    protected $fillable = [ 'code', 'price' ];
}
