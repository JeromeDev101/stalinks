<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $table = 'article';
    protected $guarded = [];

    public function country() {
        return $this->belongsTo('App\Models\Country', 'id_language', 'id');
    }

    public function backlinks() {
        return $this->belongsTo('App\Models\Backlink', 'id_backlink', 'id');
    }

    public function price() {
        return $this->belongsTo('App\Models\Price', 'id_writer_price', 'id');
    }
}
