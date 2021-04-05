<?php

namespace App\Models;

use App\Repositories\Traits\Loggable;
use Illuminate\Database\Eloquent\Model;

class UserCountryExt extends Model
{
    use Loggable;

    protected $table = 'user_country_ext';
    protected $guarded = [];
}
