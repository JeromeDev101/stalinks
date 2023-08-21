<?php

namespace App\Models;

use App\Repositories\Traits\Loggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LinkInjection extends Model
{
    use Loggable;
    use SoftDeletes;

    protected $guarded = [];

    public function publisher()
    {
        return $this->belongsTo('App\Models\Publisher', 'publisher_id')->withTrashed();
    }

    public function buyer()
    {
        return $this->belongsTo('App\Models\User', 'buyer_id');
    }
}
