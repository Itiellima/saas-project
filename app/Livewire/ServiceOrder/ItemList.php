<?php

namespace App\Livewire\ServiceOrder;

use Livewire\Component;
use App\Models\ServiceOrder;
use App\Models\ServiceOrderItem;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use App\Models\StockMovement;
use Illuminate\Support\Facades\DB;
use App\Models\Item;

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
        DB::transaction(function () use ($id) {

            // Recupera o item da ordem de serviço
            $serviceOrderItem = ServiceOrderItem::findOrFail($id);

            // Recupera o item correspondente
            $item = Item::findOrFail($serviceOrderItem->item_id);

            // Cria o movimento de estoque
            StockMovement::create([
                'tenant_id' => Auth::user()->tenant_id,
                'item_id' => $item->id,
                'service_order_id' => $serviceOrderItem->service_order_id,
                'quantity' => $serviceOrderItem->quantity,
                'type' => 'in',
                'reason' => 'Entrada de volta para estoque após exclusão do item da ordem de serviço #' . $this->serviceOrder->id,
            ]);

            // Atualiza o estoque do item
            $item->increment('stock', $serviceOrderItem->quantity);
            $item->save();

            // Exclui o item da ordem de serviço
            $serviceOrderItem->delete();
        });
    }

    public function render()
    {
        return view('livewire.service-order.item-list', [
            'items' => $this->serviceOrder
                ->items()
                ->latest()
                ->get(),

            'total' => $this->serviceOrder
                ->items()
                ->sum('total'),
        ]);
    }
}
