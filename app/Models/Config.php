<?php

namespace App\Models;

use App\Repositories\Traits\Loggable;
use Illuminate\Database\Eloquent\Model;

class Config extends Model
{
    use Loggable;

    protected $table = 'configs';
    protected $guarded = [];
}
