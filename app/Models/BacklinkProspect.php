<?php

namespace App\Models;

use App\Repositories\Traits\Loggable;
use Illuminate\Database\Eloquent\Model;

class BacklinkProspect extends Model
{
    use Loggable;

    protected $table = 'backlink_prospect';
    protected $guarded = [];
}
