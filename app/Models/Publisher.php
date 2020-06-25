<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Publisher extends Model
{
    protected $guarded = [];
    protected $table = 'publisher';

    public function user() {
        return $this->belongsTo('App\Models\User', 'user_id');
    }
}
