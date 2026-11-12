<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceOrder extends Model
{
    use HasFactory;

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
        'finished_at',
        'reopened_at',
        'reopen_reason',
        'description',
        'total',
    ];

    protected $casts = [
        'vehicle_enter' => 'datetime',
        'vehicle_leave' => 'datetime',
        'finished_at' => 'datetime',
        'reopened_at' => 'datetime',
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

    public function recalculateTotal(): void
    {
        $this->update(['total' => $this->items()->sum('total')]);
    }

    /**
     * Finished or cancelled orders cannot have items, values or data changed.
     */
    public function isLocked(): bool
    {
        return in_array($this->status, ['finished', 'cancelled']);
    }

}
