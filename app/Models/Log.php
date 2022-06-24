<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    protected $table = 'logs';
    protected $guarded = [];

    protected $fillable = [
        'table',
        'action',
        'user_id',
        'former',
        'payload',
        'page'
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }
}
