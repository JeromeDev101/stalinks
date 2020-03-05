<?php

namespace App\Models;

use App\Models\Country;
use Illuminate\Database\Eloquent\Model;

class IntDomain extends Model
{
    protected $table = 'int_domains';
    protected $guarded = [];

    public function hostingProvider()
    {
        return $this->belongsTo('App\Models\HostingProvider', 'hosting_provider_id');
    }

    public function domainProvider()
    {
        return $this->belongsTo('App\Models\DomainProvider', 'domain_provider_id');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    public function usersAccessable()
    {
        return $this->belongsToMany('App\Models\User', 'user_int_domain');
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function backlinks()
    {
        return $this->hasMany('App\Models\Backlink', 'int_domain_id');
    }
}
