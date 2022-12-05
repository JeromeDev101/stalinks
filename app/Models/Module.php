<?php

namespace App\Models;

use App\Repositories\Traits\Loggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Module extends Model
{
    use SoftDeletes, Loggable;

    protected $fillable = [
        'group',
        'page',
        'description',
    ];

    public function permissions()
    {
        return $this->hasMany('App\Models\Permission', 'module_id');
    }
}
