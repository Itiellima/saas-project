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
        // A tela já desabilita o botão; isto impede a chamada direta pelo navegador
        abort_if($this->serviceOrder->fresh()->isLocked(), 403, 'Esta OS está finalizada ou cancelada e não pode ser alterada.');

        DB::transaction(function () use ($id) {

            // Recupera o item da ordem de serviço (só desta OS, o $id vem do navegador)
            $serviceOrderItem = $this->serviceOrder->items()->findOrFail($id);

            // Recupera o item correspondente (pode ter sido excluído do cadastro)
            $item = Item::where('tenant_id', Auth::user()->tenant_id)
                ->find($serviceOrderItem->item_id);

            if ($item && $item->type === 'product') {
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
            }

            // Exclui o item da ordem de serviço
            $serviceOrderItem->delete();

            $this->serviceOrder->recalculateTotal();
        });

        $this->dispatch('item-removed');
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
