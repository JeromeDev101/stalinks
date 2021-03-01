<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Continent extends Model
{

    protected $table = 'continents';
    protected $guarded = [];

    public function countries()
    {
        return $this->hasMany('App\Models\Country', 'continent_id');
    }
}
