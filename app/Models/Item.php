<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{

    protected $table = 'items';
    
    protected $fillable = [
        'id',
        'tenant_id',
        'name',
        'type',
        'stock',
        'description',
        'cost_price',
        'sale_price',
        'quantity',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}
