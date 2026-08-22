<?php

namespace App\Livewire\ServiceOrder;

use App\Models\Item;
use App\Models\ServiceOrder;
use App\Models\ServiceOrderItem;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ItemForm extends Component
{

    public ServiceOrder $serviceOrder;

    public $item_id = null;

    public $quantity = 1;

    public $discount = 0;

    public function save()
    {
        $this->validate([
            'item_id' => ['required', 'exists:items,id'],
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $item = Item::where('tenant_id', Auth::user()->tenant_id)
            ->findOrFail($this->item_id);

        ServiceOrderItem::create([
            'tenant_id' => Auth::user()->tenant_id,
            'service_order_id' => $this->serviceOrder->id,

            'item_id' => $item->id,

            'name' => $item->name,
            'type' => $item->type,

            'quantity' => $this->quantity,
            'price' => $item->sale_price,

            'total' => ($item->sale_price * $this->quantity) - $this->discount,

        ]);


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

    public function render()
    {
        $items = Item::where('tenant_id', Auth::user()->tenant_id)->orderBy('name')->get();

        return view('livewire.service-order.item-form', compact('items'));
    }
}
