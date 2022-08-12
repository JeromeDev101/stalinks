<?php

namespace App\Models;

use App\Repositories\Traits\Loggable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Purchases extends Model
{
    use SoftDeletes, Loggable;

    protected $appends = ['full_clean_date'];

    protected $fillable = [
        'user_id',
        'type_id',
        'amount',
        'from',
        'payment_type_id',
        'is_manual',
        'notes',
        'purchased_at',
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    public function type()
    {
        return $this->belongsTo('App\Models\PurchaseType', 'type_id');
    }

    public function paymentType() {
        return $this->belongsTo('App\Models\PaymentType', 'payment_type_id');
    }

    public function purchases()
    {
        return $this->morphMany('App\Models\Purchases', 'purchaseable');
    }

    public function files()
    {
        return $this->morphMany('App\Models\File', 'fileable');
    }

    public function receipt()
    {
        return $this->morphOne('App\Models\File', 'fileable')->where('is_receipt', 1);
    }

    public function invoice()
    {
        return $this->morphOne('App\Models\File', 'fileable')->where('is_invoice', 1);
    }

    public function purchaseable () {
        return $this->morphTo();
    }

    public function getPurchasedAtAttribute($value) {
        return Carbon::parse($value)->format('Y-m-d');
    }

    public function getFullCleanDateAttribute()
    {
        $carbonDate = Carbon::parse($this->purchased_at);

        return $carbonDate->format('M j, Y');
    }
}
