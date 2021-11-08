<?php

namespace App\Models;

use App\Repositories\Traits\Loggable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
    use Loggable;

    protected $guarded = [];
    protected $table = 'replies';

    protected $appends = ['clean_date', 'full_clean_date', 'duration_date'];

    public function getCleanDateAttribute()
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

    public function getFullCleanDateAttribute()
    {
        $carbonDate = Carbon::parse($this->created_at);

        return $carbonDate->format('M j Y, g:i A');
    }

    public function getDurationDateAttribute()
    {
        $created = Carbon::parse($this->created_at);

        return $created->diffInDays(Carbon::now());
    }

    public function thread() {
        return $this->hasMany(Reply::class, 'subject','subject');
    }
}
