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

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'company_name',
        'company_url',
        'type',
        'is_sub_account',
        'skype',
        'status',
        'is_freelance',
        'account_validation',
        'info',
        'address',
        'country_id',
        'id_payment_type',
        'payment_email',
        'payment_account',
        'commission',
        'verification_code',
        'credit_auth',
        'team_in_charge',
        'username',
        'paypal_account',
        'btc_account',
        'skrill_account',
        'reminded_at',
        ];

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
