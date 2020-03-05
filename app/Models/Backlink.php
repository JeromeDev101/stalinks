<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Backlink extends Model
{
    protected $table = 'backlinks';
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    public function intDomain()
    {
        return $this->belongsTo(IntDomain::class);
    }

    public function extDomain()
    {
        return $this->belongsTo('App\Models\ExtDomain', 'ext_domain_id', 'id');
    }
}
