<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BacklinkStatus extends Model
{
    protected $table = 'backlinks_status_log';

    protected $fillable = [
        'backlink_id',
        'status'
    ];
}
