<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockMovement extends Model
{
    protected $table = 'stock_movements';
    //
    protected $fillable = [
        'tenant_id',
        'item_id',
        'service_order_id',
        'quantity',
        'type',
        'reason'
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function scopeOut($query)
    {
        return $query->where('type', 'out');
    }
}

