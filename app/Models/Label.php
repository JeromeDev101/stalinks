<?php

namespace App\Models;

use App\Repositories\Traits\Loggable;
use Illuminate\Database\Eloquent\Model;

class Label extends Model
{
    use Loggable;

    protected $table = 'labels';
    protected $guarded = [];
}
