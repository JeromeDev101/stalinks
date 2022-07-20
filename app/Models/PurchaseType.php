<?php

namespace App\Models;

use App\Repositories\Traits\Loggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseType extends Model
{
    use SoftDeletes, Loggable;

    protected $fillable = [
        'name',
        'description',
        'purchase_category_id'
    ];

    public function category()
    {
        return $this->belongsTo('App\Models\PurchaseCategory', 'purchase_category_id');
    }
}
