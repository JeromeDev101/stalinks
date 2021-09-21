<?php

namespace App\Models;

use App\Repositories\Traits\Loggable;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use Loggable;

    protected $table = 'article';
    protected $guarded = [];

    protected $appends = ['contentnohtml'];

    public function getContentNoHtmlAttribute() {
        return strip_tags($this->content);
    }

    public function user() {
        return $this->belongsTo('App\Models\User', 'id_writer');
    }

    public function country() {
        return $this->belongsTo('App\Models\Country', 'id_language', 'id');
    }

    public function backlink() {
        return $this->belongsTo('App\Models\Backlink', 'id_backlink', 'id');
    }

    public function price() {
        return $this->belongsTo('App\Models\Price', 'id_writer_price', 'id');
    }

    public function language()
    {
        return $this->belongsTo('App\Models\Language', 'id_language', 'id');
    }

    public function writer_fee()
    {
        return $this->belongsTo('App\Models\BillingWriter', 'id_article');
    }

}
