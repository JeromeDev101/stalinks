<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MailSignature extends Model
{
    protected $fillable = [
        'name',
        'content',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
