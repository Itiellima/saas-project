<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $table = 'items';

    protected $fillable = [
        'tenant_id',
        'name',
        'type',
        'stock',
        'description',
        'cost_price',
        'sale_price',
        'quantity',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function serviceOrderItems()
    {
        return $this->hasMany(ServiceOrderItem::class);
    }

    /**
     * Item já usado em alguma OS: não pode ser excluído, só desativado.
     */
    public function isUsed(): bool
    {
        return $this->serviceOrderItems()->exists();
    }

    public function scopeActive($query)
    {
        return $query->where('active', true);
    }
}
