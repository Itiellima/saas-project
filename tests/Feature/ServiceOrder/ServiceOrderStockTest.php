<?php

use App\Livewire\ServiceOrder\ItemForm;
use App\Livewire\ServiceOrder\ItemList;
use App\Models\Item;
use App\Models\ServiceOrder;
use App\Models\StockMovement;
use App\Models\User;
use Livewire\Livewire;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->serviceOrder = ServiceOrder::factory()->create(['tenant_id' => $this->user->tenant_id]);

    $this->actingAs($this->user);
});

test('a service can be added even without stock', function () {
    $service = Item::factory()->service()->create(['tenant_id' => $this->user->tenant_id]);

    Livewire::test(ItemForm::class, ['serviceOrder' => $this->serviceOrder])
        ->set('item_id', $service->id)
        ->call('save')
        ->assertHasNoErrors();

    expect($this->serviceOrder->items()->count())->toBe(1)
        ->and(StockMovement::count())->toBe(0);
});

test('adding a product takes it out of stock and records the movement', function () {
    $product = Item::factory()->create(['tenant_id' => $this->user->tenant_id, 'stock' => 5]);

    Livewire::test(ItemForm::class, ['serviceOrder' => $this->serviceOrder])
        ->set('item_id', $product->id)
        ->set('quantity', 2)
        ->call('save')
        ->assertHasNoErrors();

    expect($product->fresh()->stock)->toBe(3)
        ->and(StockMovement::first()->service_order_id)->toBe($this->serviceOrder->id);
});

test('a product without enough stock cannot be added', function () {
    $product = Item::factory()->create(['tenant_id' => $this->user->tenant_id, 'stock' => 1]);

    Livewire::test(ItemForm::class, ['serviceOrder' => $this->serviceOrder])
        ->set('item_id', $product->id)
        ->set('quantity', 2)
        ->call('save')
        ->assertHasErrors('quantity');

    expect($this->serviceOrder->items()->count())->toBe(0);
});

test('removing a product puts it back in stock', function () {
    $product = Item::factory()->create(['tenant_id' => $this->user->tenant_id, 'stock' => 5]);

    Livewire::test(ItemForm::class, ['serviceOrder' => $this->serviceOrder])
        ->set('item_id', $product->id)
        ->set('quantity', 2)
        ->call('save');

    Livewire::test(ItemList::class, ['serviceOrder' => $this->serviceOrder])
        ->call('deleteItemList', $this->serviceOrder->items()->first()->id);

    expect($product->fresh()->stock)->toBe(5);
});

test('an item from another service order cannot be removed', function () {
    $otherUser = User::factory()->create();
    $otherOrder = ServiceOrder::factory()->create(['tenant_id' => $otherUser->tenant_id]);
    $otherItem = Item::factory()->service()->create(['tenant_id' => $otherUser->tenant_id]);

    $this->actingAs($otherUser);
    Livewire::test(ItemForm::class, ['serviceOrder' => $otherOrder])
        ->set('item_id', $otherItem->id)
        ->call('save');
    $otherOrderItemId = $otherOrder->items()->first()->id;

    $this->actingAs($this->user);
    expect(fn () => Livewire::test(ItemList::class, ['serviceOrder' => $this->serviceOrder])
        ->call('deleteItemList', $otherOrderItemId))
        ->toThrow(Illuminate\Database\Eloquent\ModelNotFoundException::class);

    expect($otherOrder->items()->count())->toBe(1);
});
