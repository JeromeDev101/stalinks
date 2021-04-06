<?php

namespace App\Models;

use App\Repositories\Traits\Loggable;
use Illuminate\Database\Eloquent\Model;

class Notif extends Model
{
    use Loggable;

    protected $table = 'notifications';

    protected $fillable = [
        'notification',
        'url',
        'user_id',
        'is_read'
    ];
}
