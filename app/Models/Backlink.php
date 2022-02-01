<?php

namespace App\Models;

use App\Repositories\Traits\Loggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Backlink extends Model
{
    use Loggable;

    protected $table = 'backlinks';
    protected $guarded = [];
    protected $appends = ['affiliate_commission'];

    use SoftDeletes;

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    public function publisher()
    {
        return $this->belongsTo('App\Models\Publisher', 'publisher_id')->withTrashed();
    }

    public function billing() {
        return $this->hasMany('App\Models\Billing', 'id_backlink');
    }

    public function article() {
        return $this->hasOne('App\Models\Article', 'id_backlink');
    }

    public function getAffiliateCommissionAttribute()
    {
        $users_with_affiliates = get_buyer_id_with_affiliates();

        $backlink_fees = get_backlink_billing_fees();

        if (in_array($this->user_id, $users_with_affiliates)) {

            $billing_fee = 0;

            if (array_key_exists($this->id, $backlink_fees)) {
                $billing_fee = $backlink_fees[$this->id];
            }

            $percentage = settings('affiliate_percentage') ?: 0;

            if ($this->prices === '' || $this->prices === null) {
                return 0;
            } else {
                $prices = $this->prices;
                $price = $this->price ?: 0;

                $net_income = $prices - $price - $billing_fee;

                return (float) number_format(($percentage / 100) * $net_income, 2);
            }

        } else {
            return 0;
        }
    }
}
