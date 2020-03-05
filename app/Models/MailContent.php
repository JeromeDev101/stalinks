<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MailContent extends Model
{
    protected $table = 'mail_contents';
    protected $guarded = [];

    public function country()
    {
        return $this->belongsTo('App\Models\Country', 'country_id');
    }
}
