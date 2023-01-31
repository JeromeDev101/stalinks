<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SurveyTwo extends Model
{
    protected $table = 'survey_twos';

    protected $fillable = [
        'user_id',
        'one',
        'two',
        'three',
        'four',
        'five',
        'six',
        'seven',
        'one_other',
        'two_other',
        'three_other',
        'four_other',
        'five_other',
        'six_other',
        'seven_other',
        'set',
        'comment',
        'type'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
