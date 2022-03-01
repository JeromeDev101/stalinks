<?php

namespace App\Models;

use App\Repositories\Traits\Loggable;
use Illuminate\Database\Eloquent\Model;

class Billing extends Model
{
    use Loggable;

    protected $table = 'billing';
    protected $guarded = [];

    public function user() {
        return $this->belongsTo('App\Models\User', 'id_user');
    }

    public function backlink() {
        return $this->belongsTo('App\Models\Backlink', 'id_backlink');
    }
}
