<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceOrderItem extends Model
{
    protected $fillable = [
        'tenant_id',
        'service_order_id',
        'item_id',
        'name',
        'type',
        'quantity',
        'price',
        'discount',
        'total',
    ];

    public function serviceOrder()
    {
        return $this->belongsTo(ServiceOrder::class);
    }
}
