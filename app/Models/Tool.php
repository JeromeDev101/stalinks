<?php

namespace App\Models;

use App\Repositories\Traits\Loggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tool extends Model
{
    use SoftDeletes, Loggable;

    protected $fillable = [
        'url',
        'name',
        'details',
        'username',
        'password',
    ];

    public function purchases () {
        return $this->morphMany('App\Models\Purchases', 'purchaseable');
    }
}
