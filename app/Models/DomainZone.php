<?php

namespace App\Models;

use App\Repositories\Traits\Loggable;
use Illuminate\Database\Eloquent\Model;

class DomainZone extends Model
{
    use Loggable;

    protected $table = 'domain_zones';
    protected $guarded = [];
}
