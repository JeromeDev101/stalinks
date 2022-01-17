<?php

namespace App\Models;

use App\Repositories\Traits\Loggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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

        if (in_array($this->user_id, $users_with_affiliates)) {

            $percentage = settings('affiliate_percentage') ?: 0;

            if ($this->prices === '' || $this->prices === null) {
                return 0;
            } else {
                return (float) number_format(($percentage / 100) * $this->prices, 2);
            }

        } else {
            return 0;
        }
    }
}
