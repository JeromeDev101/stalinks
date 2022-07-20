<?php

namespace App\Models;

use App\Repositories\Traits\Loggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseCategory extends Model
{
    use SoftDeletes, Loggable;

    protected $fillable = [
        'name',
        'description',
    ];

    public function types()
    {
        return $this->hasMany('App\Models\PurchaseType', 'purchase_category_id');
    }
}
