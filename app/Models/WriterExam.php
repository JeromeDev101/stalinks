<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WriterExam extends Model
{
    protected $table = 'writer_exam';
    protected $guarded = [];

    public function writer() {
        return $this->belongsTo('App\Models\User', 'writer_id');
    }
}
