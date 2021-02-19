<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notif extends Model
{
    protected $table = 'notifications';

    protected $fillable = [
        'notification',
        'url',
        'user_id',
        'is_read'
    ];
}