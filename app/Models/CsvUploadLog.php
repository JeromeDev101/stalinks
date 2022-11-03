<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class CsvUploadLog extends Model
{
    public $timestamps = true;

    protected $table = 'csv_upload_log';

    protected $appends = [
        'duration',
        'date_only'
    ];

    protected $fillable = [
            'user_id',
            'status',
            'valid',
            'invalid',
            'valid_count',
            'invalid_count',
            'message',
            'start',
            'end',
        ];

    public function user() {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    public function getDurationAttribute() {
        return Carbon::parse($this->end)->diffForHumans($this->start);
    }

    public function getDateOnlyAttribute() {
        return Carbon::parse($this->created_at)->toDateString();
    }
}
