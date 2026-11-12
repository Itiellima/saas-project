<?php

namespace App\Livewire\ServiceOrder;

use App\Models\Item;
use App\Models\ServiceOrder;
use App\Models\ServiceOrderItem;
use App\Models\StockMovement;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class ItemForm extends Component
{

    public ServiceOrder $serviceOrder;

    public $item_id = null;

    public $quantity = 1;

    public $discount = 0;

    public function save()
    {
        if ($this->serviceOrder->fresh()->isLocked()) {
            $this->addError('item_id', 'Esta OS está finalizada ou cancelada e não pode ser alterada.');
            return;
        }

        $this->validate([
            'item_id' => ['required', 'exists:items,id'],
            'quantity' => ['required', 'integer', 'min:1'],
            'discount' => ['nullable', 'numeric', 'min:0'],
        ]);

        // Validação tenant
        $item = Item::where('tenant_id', Auth::user()->tenant_id)
            ->findOrFail($this->item_id);

        // Verifica se a quantidade solicitada é maior do que a quantidade disponivel em estoque
        // Serviços não têm estoque
        if ($item->type === 'product' && $item->stock < $this->quantity) {
            $this->addError('quantity', 'A quantidade solicitada é maior do que a quantidade disponível em estoque.');
            return;
        }

        // Verifica se o desconto é maior do que o subtotal
        $subtotal = $item->sale_price * $this->quantity;

        if ($this->discount > $subtotal) {
            $this->addError('discount', 'O desconto não pode ser maior do que o subtotal.');
            return;
        }


        // Atualiza o estoque do item
        DB::transaction(function () use ($item) {

            $serviceOrderItem = ServiceOrderItem::create([
                'tenant_id' => Auth::user()->tenant_id,
                'service_order_id' => $this->serviceOrder->id,

                'item_id' => $item->id,

                'name' => $item->name,
                'type' => $item->type,

                'quantity' => $this->quantity,
                'price' => $item->sale_price,
                'discount' => $this->discount ?: 0,

                'total' => ($item->sale_price * $this->quantity) - ($this->discount ?: 0),

            ]);

            if ($item->type === 'product') {
                // Cria o movimento de estoque
                StockMovement::create([
                    'tenant_id' => Auth::user()->tenant_id,
                    'item_id' => $item->id,
                    'service_order_id' => $serviceOrderItem->service_order_id,
                    'quantity' => $this->quantity,
                    'type' => 'out',
                    'reason' => 'Saída para ordem de serviço #' . $this->serviceOrder->id,
                ]);

                // Atualiza o estoque do item
                $item->decrement('stock', $this->quantity);
            }

            $this->serviceOrder->recalculateTotal();
        });



        $this->reset([
            'item_id',
            'quantity',
        ]);

        $this->quantity = 1;
        $this->discount = 0;


        $this->dispatch('item-added');
    }

    public function mount(ServiceOrder $serviceOrder)
    {
        $this->serviceOrder = $serviceOrder;
    }

    #[On('item-added')]
    public function refreshItems() {}

    public function render()
    {
        $items = Item::where('tenant_id', Auth::user()->tenant_id)
            ->orderBy('name')
            ->get();

        return view('livewire.service-order.item-form', compact('items'));
    }
}
