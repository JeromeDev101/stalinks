<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Registration extends Model
{
    protected $guarded = [];
    protected $table = 'registration';

    public function team_in_charge() {
        return $this->belongsTo('App\Models\User', 'team_in_charge');
    }
}
