<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

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
