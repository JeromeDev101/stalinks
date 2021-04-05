<?php

namespace App\Models;

use App\Repositories\Traits\Loggable;
use Illuminate\Database\Eloquent\Model;

class BillingWriter extends Model
{
    use Loggable;

    protected $table = 'billing_writer';
    protected $guarded = [];
}
