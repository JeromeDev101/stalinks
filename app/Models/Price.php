<?php

namespace App\Models;

use App\Repositories\Traits\Loggable;
use Illuminate\Database\Eloquent\Model;

class Price extends Model
{
    use Loggable;

    protected $table = 'price';
    protected $guarded = [];
}
