<?php

use App\Livewire\ServiceOrder\ItemForm;
use App\Models\Item;
use App\Models\ServiceOrder;
use App\Models\User;
use Livewire\Livewire;

beforeEach(function () {
    $this->owner = User::factory()->create();
    $this->owner->forceFill(['role' => 'admin'])->save();

    $this->employee = User::factory()->employee()->create(['tenant_id' => $this->owner->tenant_id]);

    $this->item = Item::factory()->create(['tenant_id' => $this->owner->tenant_id, 'name' => 'Filtro de óleo', 'sale_price' => 35]);
    $this->order = ServiceOrder::factory()->create(['tenant_id' => $this->owner->tenant_id, 'status' => 'open']);

    $this->actingAs($this->employee);
});

test('an employee works on service orders normally', function () {
    $this->get(route('service-orders.index'))->assertOk();
    $this->get(route('service-orders.create'))->assertOk();
    $this->get(route('service-orders.show', $this->order->id))->assertOk();
    $this->get(route('service-orders.edit', $this->order->id))->assertOk();
    $this->get(route('service-orders.print', $this->order->id))->assertOk();

    Livewire::test(ItemForm::class, ['serviceOrder' => $this->order])
        ->set('item_id', $this->item->id)
        ->call('save')
        ->assertHasNoErrors();

    $this->put(route('service-orders.close', $this->order->id));

    expect($this->order->fresh()->status)->toBe('finished');
});

test('an employee sees the items but can not change them', function () {
    $this->get(route('items.index'))
        ->assertOk()
        ->assertSee('Filtro de óleo')
        ->assertDontSee(route('items.create'))
        ->assertDontSee(route('items.edit', $this->item->id));

    $this->get(route('items.create'))->assertNotFound();
    $this->post(route('items.store'), ['name' => 'Novo', 'type' => 'service', 'sale_price' => 10])->assertNotFound();
    $this->get(route('items.edit', $this->item->id))->assertNotFound();
    $this->put(route('items.update', $this->item->id), ['name' => 'Filtro', 'type' => 'product', 'sale_price' => 1])->assertNotFound();
    $this->put(route('items.toggle', $this->item->id))->assertNotFound();
    $this->delete(route('items.destroy', $this->item->id))->assertNotFound();

    expect($this->item->fresh()->sale_price)->toEqual(35)
        ->and(Item::count())->toBe(1);
});

test('an employee can not cancel, reopen or delete a service order', function () {
    $this->get(route('service-orders.show', $this->order->id))
        ->assertDontSee('Cancelar OS')
        ->assertDontSee('Excluir OS')
        ->assertDontSee('cancelModal');

    $this->put(route('service-orders.cancel', $this->order->id), [
        'cancel_reason' => 'Cliente desistiu do serviço.',
        'cancel_confirm' => '1',
    ])->assertNotFound();
    $this->delete(route('service-orders.destroy', $this->order->id))->assertNotFound();

    $this->order->update(['status' => 'finished', 'finished_at' => now()]);

    $this->get(route('service-orders.show', $this->order->id))->assertDontSee('Reabrir OS');
    $this->put(route('service-orders.reopen', $this->order->id), [
        'reopen_reason' => 'Faltou lançar um item.',
        'reopen_confirm' => '1',
    ])->assertNotFound();

    expect($this->order->fresh()->status)->toBe('finished');
});

test('an employee does not see money values on the dashboard', function () {
    $this->get(route('dashboard'))
        ->assertOk()
        ->assertSee('OS em aberto')
        ->assertDontSee('Faturamento do mês')
        ->assertDontSee('Ticket médio')
        ->assertViewHas('stats', fn ($stats) => ! array_key_exists('revenue', $stats));
});

test('the owner keeps every action', function () {
    $this->actingAs($this->owner);

    $this->get(route('dashboard'))->assertSee('Faturamento do mês')->assertSee('Ticket médio');
    $this->get(route('items.index'))->assertSee(route('items.create'));
    $this->get(route('items.edit', $this->item->id))->assertOk();
    $this->get(route('service-orders.show', $this->order->id))
        ->assertSee('Cancelar OS')
        ->assertSee('Excluir OS');
});
