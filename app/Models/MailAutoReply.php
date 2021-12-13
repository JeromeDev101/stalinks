<?php

namespace App\Models;

use App\Repositories\Traits\Loggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MailAutoReply extends Model
{
    use SoftDeletes, Loggable;

    protected $fillable = [
        'body',
        'active',
        'user_id',
        'subject',
        'received',
    ];

    public function user() {
        return $this->belongsTo('App\Models\User', 'user_id');
    }
}
