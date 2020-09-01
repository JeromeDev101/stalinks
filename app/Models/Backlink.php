<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Backlink extends Model
{
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
