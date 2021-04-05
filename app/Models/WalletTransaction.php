<?php

namespace App\Models;

use App\Repositories\Traits\Loggable;
use Illuminate\Database\Eloquent\Model;

class WalletTransaction extends Model
{
    use Loggable;

    protected $table = 'wallet_transactions';
    protected $guarded = [];

    public function user() {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    public function payment_type() {
        return $this->belongsTo('App\Models\PaymentType', 'payment_via_id');
    }
}
