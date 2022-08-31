<?php

namespace App\Models;

use App\Repositories\Traits\Loggable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MailDraft extends Model
{
    use SoftDeletes, Loggable;

    protected $fillable = [
        'cc',
        'bcc',
        'body',
        'mode',
        'user_id',
        'subject',
        'received',
    ];

    protected $appends = ['human_readable_date'];

    public function getHumanReadableDateAttribute()
    {
        $carbonDate = Carbon::parse($this->created_at);

        if ($carbonDate->isToday()) {
            return $carbonDate->diffForHumans();
        } else if ($carbonDate->diffInDays(Carbon::now()) < 31) {
            return $carbonDate->format('M j');
        } else {
            return $carbonDate->format('m/d/Y');
        }
    }

    public function user() {
        return $this->belongsTo('App\Models\User', 'user_id');
    }
}
