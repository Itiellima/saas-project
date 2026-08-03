<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceOrder extends Model
{
    //
    protected $fillable = [
        'tenant_id',
        'customer_id',
        'vehicle_id',
        'name',
        'customer_name',
        'customer_phone',
        'vehicle_plate',
        'vehicle_model',
        'vehicle_km',
        'vehicle_enter',
        'vehicle_leave',
        'status',
        'description',
        'total',
    ];

    protected $casts = [
        'vehicle_enter' => 'datetime',
        'vehicle_leave' => 'datetime',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    // public function customer()
    // {
    //     return $this->belongsTo(Customer::class);
    // }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function items()
    {
        return $this->hasMany(ServiceOrderItem::class);
    }


}
