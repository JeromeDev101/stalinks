<?php

namespace App\Models;

use App\Repositories\Traits\Loggable;
use Illuminate\Database\Eloquent\Model;

class Formula extends Model
{
    use Loggable;

    protected $table = 'formula';
    protected $guarded = [];
}
