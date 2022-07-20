<?php

namespace App\Models;

use App\Repositories\Traits\Loggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class File extends Model
{
    use SoftDeletes, Loggable;

    protected $fillable = [
        'path'
    ];

    public function fileable () {
        return $this->morphTo();
    }
}
