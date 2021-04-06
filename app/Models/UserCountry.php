<?php

namespace App\Models;

use App\Repositories\Traits\Loggable;
use Illuminate\Database\Eloquent\Model;

class UserCountry extends Model
{
    use Loggable;

    protected $table = 'user_country';
    protected $guarded = [];
}
