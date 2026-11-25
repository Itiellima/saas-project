<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'created_by',
        'finished_by',
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
        'cancelled_at',
        'cancel_reason',
        'description',
        'total',
    ];

    protected $casts = [
        'vehicle_enter' => 'datetime',
        'vehicle_leave' => 'datetime',
        'finished_at' => 'datetime',
        'reopened_at' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    // public function customer()
    // {
    //     return $this->belongsTo(Customer::class);
    // }

    /**
     * Quem abriu a OS (vazio nas OS anteriores a este registro).
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Quem finalizou a OS pela última vez (limpo ao reabrir).
     */
    public function finisher()
    {
        return $this->belongsTo(User::class, 'finished_by');
    }

    /**
     * "Aberta por João · Finalizada por Pedro" (só as partes conhecidas).
     */
    public function authorshipLine(): ?string
    {
        $line = collect([
            $this->creator ? 'Aberta por ' . $this->creator->name : null,
            $this->finisher ? 'Finalizada por ' . $this->finisher->name : null,
        ])->filter()->implode(' · ');

        return $line !== '' ? $line : null;
    }

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

    /**
     * Cor, ícone e nome do status para exibir na tela.
     *
     * @return array{class: string, icon: string, label: string}
     */
    public function statusConfig(): array
    {
        return match ($this->status) {
            'open' => ['class' => 'primary', 'icon' => 'bi-folder2-open', 'label' => 'Aberta'],
            'in_progress' => ['class' => 'warning', 'icon' => 'bi-arrow-repeat', 'label' => 'Em andamento'],
            'finished' => ['class' => 'success', 'icon' => 'bi-check-circle', 'label' => 'Finalizada'],
            'cancelled' => ['class' => 'danger', 'icon' => 'bi-x-circle', 'label' => 'Cancelada'],
            default => ['class' => 'secondary', 'icon' => 'bi-question-circle', 'label' => 'Desconhecido'],
        };
    }

    /**
     * Uma OS aberta passa para "em andamento" quando recebe o primeiro item.
     * O status não volta sozinho se os itens forem removidos.
     */
    public function markInProgress(): void
    {
        if ($this->status === 'open') {
            $this->update(['status' => 'in_progress']);
        }
    }

}
