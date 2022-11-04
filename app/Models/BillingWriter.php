<?php

namespace App\Models;

use App\Repositories\Traits\Loggable;
use Illuminate\Database\Eloquent\Model;

class BillingWriter extends Model
{
    use Loggable;

    protected $table = 'billing_writer';
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'id_user');
    }

    public function article() {
        return $this->belongsTo('App\Models\Article', 'id_article');
    }
}
