<?php

namespace App\Models;

use App\Models\HostingProvider;
use App\Models\DomainProvider;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

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
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function isOurs() {
        return $this->where('isOurs', 0);
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

    public function internalDomains()
    {
        return $this->hasMany('App\Models\IntDomain', 'user_id');
    }

    public function UserType(){
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

    public function isAdmin() {
        return $this->type === config('constant.USER_TYPE_ADMIN');
    }

    public function isSetupWorkMail() {
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
}
