<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    protected $fillable = [
        'tenant_id',
        'plate',
        'model',
        'brand',
        'year',
        'color',
        'km',
        'obs',
    ];
    
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function serviceOrders()
    {
        return $this->hasMany(ServiceOrder::class);
    }
}
