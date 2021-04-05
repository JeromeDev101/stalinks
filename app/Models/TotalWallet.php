<?php

namespace App\Models;

use App\Repositories\Traits\Loggable;
use Illuminate\Database\Eloquent\Model;

class TotalWallet extends Model
{
    use Loggable;

    protected $table = 'total_wallet';
    protected $guarded = [];
}
