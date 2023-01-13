<?php

namespace App\Models;

use App\Repositories\Traits\Loggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AffiliateCode extends Model
{
    use SoftDeletes, Loggable;

    public function user() {
        return $this->belongsTo('App\Models\User', 'user_id');
    }
}
