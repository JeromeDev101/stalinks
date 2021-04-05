<?php

namespace App\Models;

use App\Repositories\Traits\Loggable;
use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    use Loggable;

    protected $table = 'languages';
    protected $guarded = [];
}
