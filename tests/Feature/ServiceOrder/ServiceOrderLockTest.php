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
    $this->item = Item::factory()->service()->create(['tenant_id' => $this->user->tenant_id]);

    $this->actingAs($this->user);
});

function finish(ServiceOrder $serviceOrder): void
{
    test()->put(route('service-orders.close', $serviceOrder->id));
}

test('finishing records when the order was finished', function () {
    finish($this->serviceOrder);

    $serviceOrder = $this->serviceOrder->fresh();

    expect($serviceOrder->status)->toBe('finished')
        ->and($serviceOrder->finished_at)->not->toBeNull()
        ->and($serviceOrder->isLocked())->toBeTrue();
});

test('items cannot be added to a finished order', function () {
    finish($this->serviceOrder);

    Livewire::test(ItemForm::class, ['serviceOrder' => $this->serviceOrder])
        ->set('item_id', $this->item->id)
        ->call('save')
        ->assertHasErrors('item_id');

    expect($this->serviceOrder->items()->count())->toBe(0);
});

test('items cannot be removed from a finished order', function () {
    Livewire::test(ItemForm::class, ['serviceOrder' => $this->serviceOrder])
        ->set('item_id', $this->item->id)
        ->call('save');

    finish($this->serviceOrder);

    Livewire::test(ItemList::class, ['serviceOrder' => $this->serviceOrder])
        ->call('deleteItemList', $this->serviceOrder->items()->first()->id)
        ->assertForbidden();

    expect($this->serviceOrder->items()->count())->toBe(1);
});

test('a finished order cannot be deleted', function () {
    finish($this->serviceOrder);

    $this->delete(route('service-orders.destroy', $this->serviceOrder->id))
        ->assertSessionHas('error');

    expect(ServiceOrder::find($this->serviceOrder->id))->not->toBeNull();
});

test('a finished order can be reopened with a reason and confirmation', function () {
    finish($this->serviceOrder);

    $this->put(route('service-orders.reopen', $this->serviceOrder->id), [
        'reopen_reason' => 'Item esquecido na finalização',
        'reopen_confirm' => '1',
    ])->assertSessionHasNoErrors();

    $serviceOrder = $this->serviceOrder->fresh();

    expect($serviceOrder->status)->toBe('open')
        ->and($serviceOrder->finished_at)->toBeNull()
        ->and($serviceOrder->reopened_at)->not->toBeNull()
        ->and($serviceOrder->reopen_reason)->toBe('Item esquecido na finalização')
        ->and($serviceOrder->isLocked())->toBeFalse();
});

test('reopening requires a reason and confirmation', function () {
    finish($this->serviceOrder);

    $this->put(route('service-orders.reopen', $this->serviceOrder->id), [
        'reopen_reason' => 'curto',
    ])->assertSessionHasErrors(['reopen_reason', 'reopen_confirm']);

    expect($this->serviceOrder->fresh()->status)->toBe('finished');
});

test('only finished orders can be reopened', function () {
    $this->put(route('service-orders.reopen', $this->serviceOrder->id), [
        'reopen_reason' => 'Item esquecido na finalização',
        'reopen_confirm' => '1',
    ])->assertSessionHas('error');

    expect($this->serviceOrder->fresh()->reopened_at)->toBeNull();
});

test('an order from another tenant cannot be reopened', function () {
    finish($this->serviceOrder);

    $this->actingAs(User::factory()->create());

    $this->put(route('service-orders.reopen', $this->serviceOrder->id), [
        'reopen_reason' => 'Item esquecido na finalização',
        'reopen_confirm' => '1',
    ])->assertNotFound();

    expect($this->serviceOrder->fresh()->status)->toBe('finished');
});

test('the order page shows the reopen warning', function () {
    finish($this->serviceOrder);

    $this->get(route('service-orders.show', $this->serviceOrder->id))
        ->assertOk()
        ->assertSee('Reabrir OS');

    $this->put(route('service-orders.reopen', $this->serviceOrder->id), [
        'reopen_reason' => 'Item esquecido na finalização',
        'reopen_confirm' => '1',
    ]);

    $this->get(route('service-orders.show', $this->serviceOrder->id))
        ->assertOk()
        ->assertSee('Esta OS foi reaberta em')
        ->assertSee('Item esquecido na finalização');
});
