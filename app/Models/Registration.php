<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Registration extends Model
{
    use SoftDeletes;

    protected $guarded = [];
    protected $table = 'registration';

    protected $fillable = ['reminded_at'];

    public function team_in_charge() {
        return $this->belongsTo('App\Models\User', 'team_in_charge');
    }

    public function user() {
        return $this->belongsTo('App\Models\User', 'email', 'email');
    }

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($this->attributes['created_at'])->format('Y-m-d');
    }
}
