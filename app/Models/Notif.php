<?php

namespace App\Models;

use App\Repositories\Traits\Loggable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Notif extends Model
{
    use Loggable;

    protected $table = 'notifications';

    protected $appends = [
        'human_date',
    ];

    protected $fillable = [
        'notification',
        'url',
        'user_id',
        'is_read'
    ];

    public function notifiable()
    {
        return $this->morphTo();
    }

    public function getHumanDateAttribute() {
        return Carbon::parse($this->created_at)->diffForHumans();
    }
}
