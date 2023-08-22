<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Repositories\Traits\Loggable;

class BillingInjection extends Model
{
    use Loggable;

    protected $guarded = [];
    protected $table = 'billing_injections';
}
