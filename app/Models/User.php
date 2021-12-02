<?php

namespace App\Models;

use App\Models\HostingProvider;
use App\Models\DomainProvider;
use App\Repositories\Traits\Loggable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\Publisher;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable, SoftDeletes, Loggable, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden
        = [
            'password',
            'remember_token',
        ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts
        = [
            'email_verified_at' => 'datetime',
        ];

    protected $appends = [
        'work_mail_orig'
    ];

    public function getWorkMailOrigAttribute() {
        return $this->work_mail;
    }

    public function isOurs()
    {
        return $this->where('isOurs', 0);
    }

    public function userPaymentTypes()
    {
        return $this->hasMany('App\Models\UsersPaymentType', 'user_id');
    }

    public function role()
    {
        return $this->belongsTo('App\Models\Role', 'role_id');
    }

    public function backlinks()
    {
        return $this->hasMany('App\Models\Backlink');
    }

    public function logs()
    {
        return $this->hasMany('App\Models\Log', 'user_id');
    }

    public function paymentType()
    {
        return $this->belongsTo('App\Models\PaymentType', 'id_payment_type');
    }

    public function internalDomains()
    {
        return $this->hasMany('App\Models\IntDomain', 'user_id');
    }

    public function UserType()
    {
        return $this->belongsTo('App\Models\Registration', 'email', 'email');
    }

    public function registration()
    {
        return $this->belongsTo('App\Models\Registration', 'email', 'email');
    }

    public function internalDomainsAccessable()
    {
        return $this->belongsToMany('App\Models\IntDomain', 'user_int_domain');
    }

    public function countriesAccessable()
    {
        return $this->belongsToMany('App\Models\Country', 'user_country');
    }

    public function countriesExtAccessable()
    {
        return $this->belongsToMany('App\Models\Country', 'user_country_ext');
    }

    public function isAdmin()
    {
        return $this->type === config('constant.USER_TYPE_ADMIN');
    }

    public function isSetupWorkMail()
    {
        return trim($this->work_mail) != ''
            && trim($this->work_mail_pass) != '';
    }

    public function hostings()
    {
        return $this->hasMany(HostingProvider::class);
    }

    public function domains()
    {
        return $this->hasMany(DomainProvider::class);
    }

    // public function wallet_transaction() {
    //     return $this->hasOne('App\Models\WalletTransaction', 'user_id', 'id');
    // }

    public function buyer_purchased()
    {
        return $this->hasMany('App\Models\BuyerPurchased', 'user_id_buyer', 'id');
    }

    public function total_wallet()
    {
        return $this->hasOne('App\Models\TotalWallet', 'user_id', 'id');
    }

    public function wallet_transactions()
    {
        return $this->hasMany(WalletTransaction::class, 'user_id', 'id');
    }

    public function subBuyers()
    {
        return Registration::with('user')->where('team_in_charge', $this->id)->get();
    }

    public function credits()
    {
        $subBuyers = $this->subBuyers();

        $wallet = $this->wallet_transactions()
            ->selectRaw('SUM(amount_usd) as amount_usd')
            ->where('admin_confirmation', '!=', 'Not Paid')
            ->first();

        $totalPurchased = $this->backlinks()
            ->selectRaw('SUM(price) as total_purchased')
            ->when(count($subBuyers) > 0, function($query) use ($subBuyers){
                return $query->orWhereIn('user_id', $subBuyers->pluck('users.id'));
            })
        ->first();

        return floatval($wallet->amount_usd) - floatval($totalPurchased->total_purchased);
    }

    public function access()
    {
        return $this->hasMany('App\UserWorkMails', 'user_id');
    }

    public function drafts()
    {
        return $this->hasMany('App\Models\MailDraft', 'user_id');
    }
}
