<?php

namespace App\Models;

use App\Models\User;
use App\Repositories\Traits\Loggable;
use Illuminate\Database\Eloquent\Model;

class DomainProvider extends Model
{
    use Loggable;

    protected $table = 'domain_providers';
    protected $guarded = [];

    public function internalDomains()
    {
        return $this->hasMany('App\Models\IntDomain', 'domain_provider_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
