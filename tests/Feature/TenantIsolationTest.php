<?php

use App\Livewire\ServiceOrder\Index;
use App\Livewire\ServiceOrder\ItemForm;
use App\Models\Item;
use App\Models\ServiceOrder;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Livewire\Livewire;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->other = User::factory()->create();

    $this->actingAs($this->user);
});

test('the service order list only shows orders from the own workshop', function () {
    ServiceOrder::factory()->create(['tenant_id' => $this->user->tenant_id, 'vehicle_plate' => 'MIN1A11']);
    ServiceOrder::factory()->create(['tenant_id' => $this->other->tenant_id, 'vehicle_plate' => 'OUT2B22']);

    Livewire::test(Index::class)
        ->assertSee('MIN1A11')
        ->assertDontSee('OUT2B22');
});

// Regressão: o orWhere sem agrupamento trazia OS de outras oficinas na busca
test('searching by plate or name never shows orders from another workshop', function () {
    ServiceOrder::factory()->create(['tenant_id' => $this->user->tenant_id, 'customer_name' => 'MARIA', 'vehicle_plate' => 'ABC1D23']);
    ServiceOrder::factory()->create(['tenant_id' => $this->other->tenant_id, 'customer_name' => 'INTRUSO ABC', 'vehicle_plate' => 'ABC9Z99']);

    Livewire::test(Index::class)
        ->set('search', 'ABC')
        ->assertSee('ABC1D23')
        ->assertDontSee('ABC9Z99')
        ->assertDontSee('INTRUSO');
});

test('searching combined with a status filter stays inside the workshop', function () {
    ServiceOrder::factory()->create(['tenant_id' => $this->other->tenant_id, 'vehicle_plate' => 'XYZ1A11', 'status' => 'open']);

    Livewire::test(Index::class)
        ->set('search', 'XYZ')
        ->set('status', 'open')
        ->assertDontSee('XYZ1A11');
});

test('an item from another workshop cannot be added to a service order', function () {
    $serviceOrder = ServiceOrder::factory()->create(['tenant_id' => $this->user->tenant_id]);
    $otherItem = Item::factory()->service()->create(['tenant_id' => $this->other->tenant_id]);

    expect(fn () => Livewire::test(ItemForm::class, ['serviceOrder' => $serviceOrder])
        ->set('item_id', $otherItem->id)
        ->call('save'))
        ->toThrow(ModelNotFoundException::class);

    expect($serviceOrder->items()->count())->toBe(0);
});

test('every service order page of another workshop returns not found', function () {
    $order = ServiceOrder::factory()->create(['tenant_id' => $this->other->tenant_id]);

    $this->get(route('service-orders.show', $order->id))->assertNotFound();
    $this->get(route('service-orders.edit', $order->id))->assertNotFound();
    $this->get(route('service-orders.print', $order->id))->assertNotFound();
    $this->put(route('service-orders.close', $order->id))->assertNotFound();
    $this->put(route('service-orders.reopen', $order->id))->assertNotFound();
    $this->put(route('service-orders.cancel', $order->id))->assertNotFound();
    $this->delete(route('service-orders.destroy', $order->id))->assertNotFound();

    expect($order->fresh()->status)->toBe('open');
});
