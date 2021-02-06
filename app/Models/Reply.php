<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
    protected $guarded = [];
    protected $table = 'replies';

    protected $appends = ['clean_date'];

    public function getCleanDateAttribute()
    {
        // return Carbon::createFromTimeStamp(strtotime($this->created_at))->diffForHumans();
        return Carbon::parse($this->created_at)->diffInDays(Carbon::now()) < 31 ? Carbon::parse($this->created_at)->format('M j') : Carbon::parse($this->created_at)->format('m/d/Y');
    }
}
