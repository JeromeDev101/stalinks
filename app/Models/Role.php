<?php

namespace App\Models;

use App\Repositories\Traits\Loggable;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use Loggable;

    protected $table = 'roles';
    protected $guarded = [];

    public function users()
    {
        return $this->hasMany('App\Models\User');
    }
}
