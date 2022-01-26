<?php

namespace App\Models;

use App\Repositories\Traits\Loggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    use Loggable;
    use SoftDeletes;

    protected $table = 'roles';
    protected $dates = ['deleted_at'];
    protected $guarded = [];

    public function users()
    {
        return $this->hasMany('App\Models\User');
    }
}
