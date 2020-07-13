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
}
