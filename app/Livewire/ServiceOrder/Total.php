<?php

namespace App\Livewire\ServiceOrder;

use App\Models\ServiceOrder;
use Livewire\Attributes\On;
use Livewire\Component;

class Total extends Component
{
    public ServiceOrder $serviceOrder;

    #[On('item-added')]
    #[On('item-removed')]
    public function refreshTotal()
    {
        $this->serviceOrder->refresh();
    }

    public function render()
    {
        return view('livewire.service-order.total');
    }
}
