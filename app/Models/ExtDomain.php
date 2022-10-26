<?php

namespace App\Models;
use App\Events\ExtDomainStatusUpdateEvent;
use App\Repositories\Traits\Loggable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Database\Eloquent\Model;
use Kleemans\AttributeEvents;

class ExtDomain extends Model
{
    use SoftDeletes, Loggable, AttributeEvents;

    protected $table = 'ext_domains';
    protected $guarded = [];

    protected $appends = [
        'alexa_created_at'
    ];

    protected $dispatchesEvents = [
        'created' => ExtDomainStatusUpdateEvent::class,
        'status:*' => ExtDomainStatusUpdateEvent::class,
    ];

    public function users() {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

//    public function status()
//    {
//        return $this->hasMany('App\ExtDomainStatus', 'ext_domain_id');
//    }

    public function country()
    {
        return $this->belongsTo('App\Models\Country', 'country_id');
    }

    public function backlinks()
    {
        return $this->hasMany('App\Models\Backlink', 'ext_domain_id');
    }

    public function getAlexaCreatedAtAttribute()
    {
        if ($this->alexa_rank != 0) {
            return Carbon::parse($this->created_at)->format('Y-m-d');
        } else {
            return 'N/A';
        }
    }

    public function prospect()
    {
        return $this->belongsTo('App\Models\BacklinkProspect', 'backlink_prospect_id');
    }
}
