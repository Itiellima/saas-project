<?php

use App\Livewire\ServiceOrder\ItemForm;
use App\Livewire\ServiceOrder\ItemList;
use App\Models\Item;
use App\Models\ServiceOrder;
use App\Models\User;
use Livewire\Livewire;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->serviceOrder = ServiceOrder::factory()->create(['tenant_id' => $this->user->tenant_id]);
    $this->item = Item::factory()->create(['tenant_id' => $this->user->tenant_id, 'sale_price' => 100]);

    $this->actingAs($this->user);
});

test('adding an item updates the service order total', function () {
    Livewire::test(ItemForm::class, ['serviceOrder' => $this->serviceOrder])
        ->set('item_id', $this->item->id)
        ->set('quantity', 2)
        ->set('discount', 10)
        ->call('save')
        ->assertHasNoErrors()
        ->assertDispatched('item-added');

    expect($this->serviceOrder->fresh()->total)->toEqual(190);
});

test('removing an item updates the service order total', function () {
    Livewire::test(ItemForm::class, ['serviceOrder' => $this->serviceOrder])
        ->set('item_id', $this->item->id)
        ->call('save');

    $serviceOrderItem = $this->serviceOrder->items()->first();

    Livewire::test(ItemList::class, ['serviceOrder' => $this->serviceOrder])
        ->call('deleteItemList', $serviceOrderItem->id)
        ->assertDispatched('item-removed');

    expect($this->serviceOrder->fresh()->total)->toEqual(0);
});
