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
    $this->product = Item::factory()->create(['tenant_id' => $this->user->tenant_id, 'stock' => 10]);
    $this->service = Item::factory()->service()->create(['tenant_id' => $this->user->tenant_id]);

    $this->actingAs($this->user);
});

function addItem(ServiceOrder $serviceOrder, Item $item, int $quantity = 1): void
{
    Livewire::test(ItemForm::class, ['serviceOrder' => $serviceOrder])
        ->set('item_id', $item->id)
        ->set('quantity', $quantity)
        ->call('save')
        ->assertHasNoErrors();
}

function cancelData(array $overrides = []): array
{
    return ['cancel_reason' => 'Cliente desistiu do serviço', 'cancel_confirm' => '1', ...$overrides];
}

// Cancelar

test('cancelling returns products to stock and records the movement', function () {
    addItem($this->serviceOrder, $this->product, 3);
    addItem($this->serviceOrder, $this->service);

    $this->put(route('service-orders.cancel', $this->serviceOrder->id), cancelData())
        ->assertSessionHasNoErrors()
        ->assertSessionHas('success');

    $serviceOrder = $this->serviceOrder->fresh();

    expect($serviceOrder->status)->toBe('cancelled')
        ->and($serviceOrder->cancelled_at)->not->toBeNull()
        ->and($serviceOrder->cancel_reason)->toBe('Cliente desistiu do serviço')
        ->and($serviceOrder->isLocked())->toBeTrue()
        ->and($serviceOrder->items()->count())->toBe(2)
        ->and($this->product->fresh()->stock)->toBe(10)
        ->and(StockMovement::where('type', 'in')->count())->toBe(1)
        ->and(StockMovement::where('type', 'in')->first()->reason)->toContain('Cancelamento');
});

test('cancelling requires a reason and confirmation', function () {
    $this->put(route('service-orders.cancel', $this->serviceOrder->id), ['cancel_reason' => 'curto'])
        ->assertSessionHasErrors(['cancel_reason', 'cancel_confirm']);

    expect($this->serviceOrder->fresh()->status)->toBe('open');
});

test('a finished or cancelled order cannot be cancelled', function () {
    addItem($this->serviceOrder, $this->product, 2);
    $this->put(route('service-orders.close', $this->serviceOrder->id));

    $this->put(route('service-orders.cancel', $this->serviceOrder->id), cancelData())
        ->assertSessionHas('error');

    expect($this->serviceOrder->fresh()->status)->toBe('finished')
        ->and($this->product->fresh()->stock)->toBe(8);
});

test('a cancelled order cannot be cancelled twice', function () {
    addItem($this->serviceOrder, $this->product, 2);
    $this->put(route('service-orders.cancel', $this->serviceOrder->id), cancelData());

    $this->put(route('service-orders.cancel', $this->serviceOrder->id), cancelData())
        ->assertSessionHas('error');

    expect($this->product->fresh()->stock)->toBe(10);
});

test('a cancelled order cannot be reopened, edited or receive items', function () {
    $this->put(route('service-orders.cancel', $this->serviceOrder->id), cancelData());

    $this->put(route('service-orders.reopen', $this->serviceOrder->id), [
        'reopen_reason' => 'Tentando reabrir cancelada',
        'reopen_confirm' => '1',
    ])->assertSessionHas('error');

    $this->get(route('service-orders.edit', $this->serviceOrder->id))->assertSessionHas('error');

    Livewire::test(ItemForm::class, ['serviceOrder' => $this->serviceOrder->fresh()])
        ->set('item_id', $this->service->id)
        ->call('save')
        ->assertHasErrors('item_id');

    expect($this->serviceOrder->fresh()->status)->toBe('cancelled');
});

test('an order from another tenant cannot be cancelled', function () {
    $this->actingAs(User::factory()->create());

    $this->put(route('service-orders.cancel', $this->serviceOrder->id), cancelData())->assertNotFound();

    expect($this->serviceOrder->fresh()->status)->toBe('open');
});

test('the order page shows the cancellation', function () {
    $this->get(route('service-orders.show', $this->serviceOrder->id))
        ->assertSee('Cancelar OS');

    $this->put(route('service-orders.cancel', $this->serviceOrder->id), cancelData());

    $this->get(route('service-orders.show', $this->serviceOrder->id))
        ->assertSee('Esta OS foi cancelada em')
        ->assertSee('Cliente desistiu do serviço')
        ->assertDontSee('data-bs-target="#cancelModal"', false)
        ->assertDontSee('Finalizar OS');
});

// Excluir

test('an order without items can be deleted', function () {
    $this->delete(route('service-orders.destroy', $this->serviceOrder->id))
        ->assertRedirect(route('service-orders.index'));

    expect(ServiceOrder::find($this->serviceOrder->id))->toBeNull();
});

test('an order with items cannot be deleted', function () {
    addItem($this->serviceOrder, $this->product, 2);

    $this->delete(route('service-orders.destroy', $this->serviceOrder->id))
        ->assertSessionHas('error');

    expect(ServiceOrder::find($this->serviceOrder->id))->not->toBeNull()
        ->and($this->product->fresh()->stock)->toBe(8);
});

// Em andamento automático

test('the first item moves the order to in progress', function () {
    expect($this->serviceOrder->status)->toBe('open');

    addItem($this->serviceOrder, $this->service);

    expect($this->serviceOrder->fresh()->status)->toBe('in_progress');
});

test('the status badge updates when the first item is added', function () {
    $badge = Livewire::test(App\Livewire\ServiceOrder\StatusBadge::class, ['serviceOrder' => $this->serviceOrder])
        ->assertSee('ABERTA');

    addItem($this->serviceOrder, $this->service);

    $badge->dispatch('item-added')->assertSee('EM ANDAMENTO');
});

test('removing all items keeps the order in progress', function () {
    addItem($this->serviceOrder, $this->service);

    Livewire::test(ItemList::class, ['serviceOrder' => $this->serviceOrder->fresh()])
        ->call('deleteItemList', $this->serviceOrder->items()->first()->id);

    expect($this->serviceOrder->fresh()->status)->toBe('in_progress');
});

test('an in progress order can be finished', function () {
    addItem($this->serviceOrder, $this->service);

    $this->put(route('service-orders.close', $this->serviceOrder->id));

    expect($this->serviceOrder->fresh()->status)->toBe('finished');
});

test('reopening an order with items goes back to in progress', function () {
    addItem($this->serviceOrder, $this->service);
    $this->put(route('service-orders.close', $this->serviceOrder->id));

    $this->put(route('service-orders.reopen', $this->serviceOrder->id), [
        'reopen_reason' => 'Item esquecido na finalização',
        'reopen_confirm' => '1',
    ]);

    expect($this->serviceOrder->fresh()->status)->toBe('in_progress');
});
