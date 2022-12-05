<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $fillable = [
        'module_id',
        'name',
        'display_name'
    ];

    public function module() {
        return $this->belongsTo('App\Models\Module', 'module_id');
    }

    public function roles () {
        return $this->belongsToMany('App\Models\Role');
    }
}
