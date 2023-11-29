<?php

namespace App;

use App\Repositories\Traits\Loggable;
use Illuminate\Database\Eloquent\Model;

class ChatGptSetupOption extends Model
{
    use Loggable;

    protected $guarded = [];
}
