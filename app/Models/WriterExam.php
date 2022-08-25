<?php

namespace App\Models;

use App\Repositories\Traits\Loggable;
use Illuminate\Database\Eloquent\Model;

class WriterExam extends Model
{
    use Loggable;

    protected $table = 'writer_exam';
    protected $guarded = [];

    public function writer() {
        return $this->belongsTo('App\Models\User', 'writer_id');
    }
}
