<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BacklinksInterested extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'publisher_id',
        'link',
        'anchor_text',
        'url_advertiser',
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    public function publisher()
    {
        return $this->belongsTo('App\Models\Publisher', 'publisher_id')->withTrashed();
    }
}
