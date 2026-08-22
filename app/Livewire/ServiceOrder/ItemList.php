<?php

namespace App\Livewire\ServiceOrder;

use Livewire\Component;
use App\Models\ServiceOrder;
use App\Models\ServiceOrderItem;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;

class ItemList extends Component
{

    public ServiceOrder $serviceOrder;

    public function mount(ServiceOrder $serviceOrder)
    {
        $this->serviceOrder = $serviceOrder;
    }

    #[On('item-added')]
    public function refreshItems()
    {
        // Não precisa fazer nada aqui.
        // O método apenas força um novo render.
    }

    public function deleteItemList($id)
    {
        $this->serviceOrder->items()->findOrFail($id)->delete();
    }

    public function render()
    {
        return view('livewire.service-order.item-list', [
            'items' => $this->serviceOrder
                ->items()
                ->latest()
                ->get(),
        ]);
    }
}
