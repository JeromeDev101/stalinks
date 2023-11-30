<?php

namespace App;

use App\Repositories\Traits\Loggable;
use Illuminate\Database\Eloquent\Model;

class ChatGptSetup extends Model
{
    use Loggable;

    protected $guarded = [];

    public function options() {
        return $this->hasMany('App\ChatGptSetupOption');
    }
}
