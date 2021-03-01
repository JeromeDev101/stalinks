<?php

namespace App\Models;

use App\Models\IntDomain;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $table = 'countries';
    protected $guarded = [];

    public function externalDomains()
    {
        return $this->hasMany('App\Models\ExtDomain');
    }

    public function usersAccessable()
    {
        return $this->belongsToMany('App\Models\User', 'user_country');
    }

    public function usersExtAccessable()
    {
        return $this->belongsToMany('App\Models\User', 'user_country_ext');
    }

    public function intDomains()
    {
        return $this->hasMany(IntDomain::class);
    }

    public function users()
    {
        return $this->belongsToMany('App\Models\User', 'user_country');
    }

    public function extUsers()
    {
        return $this->belongsToMany('App\Models\User', 'user_country_ext');
    }

    public function continent()
    {
        return $this->belongsTo('App\Models\Continent', 'continent_id');
    }
}
