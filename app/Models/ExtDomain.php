<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExtDomain extends Model
{
    protected $table = 'ext_domains';
    protected $guarded = [];

    public function country()
    {
        return $this->belongsTo('App\Models\Country', 'country_id');
    }

    public function backlinks()
    {
        return $this->hasMany('App\Models\Backlink', 'ext_domain_id');
    }
}
