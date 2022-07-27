<?php

namespace App\Models;

use App\Repositories\Traits\Loggable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tool extends Model
{
    use SoftDeletes, Loggable;

    protected $fillable = [
        'url',
        'name',
        'details',
        'username',
        'password',
        'expired_at',
        'registered_at'
    ];

    protected $appends = [
        'expiring_days'
    ];

    public function purchases () {
        return $this->morphMany('App\Models\Purchases', 'purchaseable');
    }

    public function getExpiringDaysAttribute()
    {
        $expiration = Carbon::parse($this->expired_at);

        return Carbon::today()->diffInDays($expiration, false);
    }
}
