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

test('the discount is saved with the item', function () {
    Livewire::test(ItemForm::class, ['serviceOrder' => $this->serviceOrder])
        ->set('item_id', $this->item->id)
        ->set('discount', 15.5)
        ->call('save');

    expect($this->serviceOrder->items()->first()->discount)->toEqual(15.5);
});

test('an empty discount is saved as zero', function () {
    Livewire::test(ItemForm::class, ['serviceOrder' => $this->serviceOrder])
        ->set('item_id', $this->item->id)
        ->set('discount', '')
        ->call('save')
        ->assertHasNoErrors();

    expect($this->serviceOrder->items()->first()->discount)->toEqual(0)
        ->and($this->serviceOrder->fresh()->total)->toEqual(100);
});

test('the discount cannot be greater than the subtotal', function () {
    Livewire::test(ItemForm::class, ['serviceOrder' => $this->serviceOrder])
        ->set('item_id', $this->item->id)
        ->set('discount', 150)
        ->call('save')
        ->assertHasErrors('discount');

    expect($this->serviceOrder->items()->count())->toBe(0);
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
