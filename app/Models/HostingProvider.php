<?php

namespace App\Models;

use App\Models\User;
use App\Repositories\Traits\Loggable;
use Illuminate\Database\Eloquent\Model;

class HostingProvider extends Model
{
    use Loggable;

    protected $table = 'hosting_providers';
    protected $guarded = [];

    public function internalDomains()
    {
        return $this->hasMany('App\Models\IntDomain', 'hosting_provider_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
