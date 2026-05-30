<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    //
    protected $fillable = [
        'tenant_id',
        'name',
        'type',
        'stock',
        'description',
        'cost_price',
        'sale_price',
        'quantity',
    ];
}
