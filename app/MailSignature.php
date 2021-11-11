<?php

namespace App;

use App\Repositories\Traits\Loggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MailSignature extends Model
{
    use SoftDeletes, Loggable;

    protected $fillable = [
        'name',
        'content',
        'user_id',
        'work_mail'
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
