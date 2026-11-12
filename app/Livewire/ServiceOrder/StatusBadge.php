<?php

namespace App\Livewire\ServiceOrder;

use App\Models\ServiceOrder;
use Livewire\Attributes\On;
use Livewire\Component;

class StatusBadge extends Component
{
    public ServiceOrder $serviceOrder;

    // O primeiro item muda o status para "em andamento" sem recarregar a página
    #[On('item-added')]
    #[On('item-removed')]
    public function refreshStatus()
    {
        $this->serviceOrder->refresh();
    }

    public function render()
    {
        return view('livewire.service-order.status-badge', [
            'status' => $this->serviceOrder->statusConfig(),
        ]);
    }
}
