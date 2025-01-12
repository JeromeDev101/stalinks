<?php

namespace App\Models;

use App\Repositories\Traits\Loggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Backlink extends Model
{
    use Loggable;

    protected $table = 'backlinks';
    protected $guarded = [];
    use SoftDeletes;

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    public function publisher()
    {
        return $this->belongsTo('App\Models\Publisher', 'publisher_id')->withTrashed();
    }

    public function billing() {
        return $this->hasMany('App\Models\Billing', 'id_backlink');
    }

    public function article() {
        return $this->hasOne('App\Models\Article', 'id_backlink');
    }
}
