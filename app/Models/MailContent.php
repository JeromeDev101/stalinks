<?php

namespace App\Models;

use App\Repositories\Traits\Loggable;
use Illuminate\Database\Eloquent\Model;

class MailContent extends Model
{
    use Loggable;

    protected $table = 'mail_contents';
    protected $guarded = [];

    public function country()
    {
        return $this->belongsTo('App\Models\Country', 'country_id');
    }

    public function language() {
        return $this->belongsTo('App\Models\Language', 'country_id');
    }
}
