<?php

namespace App\Models;

use App\Repositories\Traits\Loggable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Registration extends Model
{
    use SoftDeletes, Loggable;

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
        'language_id',
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
        'validation_reminded_at',
        'reminded_days',
        'writer_price',
        'rate_type',
        'email_via',
        'is_show_price_basis',
        'can_validate_backlink',
        'is_exam_passed',
        'affiliate_id',
        'affiliate_code_id',
        'affiliate_code',
        'deposit_reminded_at',
        'survey_code',
        'email_via_others',
        'facebook',
        'buyer_type',
        'is_recommended'
    ];

    public function team_in_charge() {
        return $this->belongsTo('App\Models\User', 'team_in_charge');
    }

    public function team_in_charge_username() {
        return $this->belongsTo('App\Models\User', 'team_in_charge');
    }

    public function in_charge() {
        return $this->belongsTo('App\Models\User', 'team_in_charge');
    }

    public function affiliate() {
        return $this->belongsTo('App\Models\User', 'affiliate_id');
    }

    public function affiliateCode() {
        return $this->belongsTo('App\Models\AffiliateCode', 'affiliate_code_id');
    }

    public function user() {
        return $this->belongsTo('App\Models\User', 'email', 'email');
    }

    public function country() {
        return $this->belongsTo('App\Models\Country', 'country_id');
    }

    public function language() {
        return $this->belongsTo('App\Models\Language', 'language_id');
    }

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($this->attributes['created_at'])->format('Y-m-d');
    }
}
