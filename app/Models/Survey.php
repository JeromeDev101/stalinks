<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Survey extends Model
{
    protected $table = 'survey';

    protected $fillable = [
        'user_id',
        'one',
        'two',
        'three',
        'four',
        'five',
        'six',
        'one_other',
        'two_other',
        'three_other',
        'four_other',
        'five_other',
        'six_other',
        'set',
        'comment',
        'type'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
