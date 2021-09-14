<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tool extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'url',
        'name',
        'details',
        'username',
        'password',
    ];
}
