<?php

namespace App\Models;

use App\Repositories\Traits\Loggable;
use Illuminate\Database\Eloquent\Model;

class Continent extends Model
{
    use Loggable;

    protected $table = 'continents';
    protected $guarded = [];

    public function countries()
    {
        return $this->hasMany('App\Models\Country', 'continent_id');
    }
}
