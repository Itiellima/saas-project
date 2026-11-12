<?php

use App\Livewire\ServiceOrder\ItemForm;
use App\Models\Item;
use App\Models\ServiceOrder;
use App\Models\User;
use Livewire\Livewire;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->user->tenant->update(['name' => 'Oficina do João', 'phone' => '(11) 3333-4444']);

    $this->serviceOrder = ServiceOrder::factory()->create([
        'tenant_id' => $this->user->tenant_id,
        'customer_name' => 'MARIA SOUZA',
        'vehicle_plate' => 'ABC1D23',
        'description' => 'TROCA DE ÓLEO',
    ]);

    $this->actingAs($this->user);
});

test('the print page shows the order, its items and the workshop', function () {
    $item = Item::factory()->service()->create([
        'tenant_id' => $this->user->tenant_id,
        'name' => 'Alinhamento',
        'sale_price' => 120,
    ]);

    Livewire::test(ItemForm::class, ['serviceOrder' => $this->serviceOrder])
        ->set('item_id', $item->id)
        ->set('discount', 20)
        ->call('save');

    $this->get(route('service-orders.print', $this->serviceOrder->id))
        ->assertOk()
        ->assertSee('Oficina do João')
        ->assertSee('(11) 3333-4444')
        ->assertSee('Nº ' . $this->serviceOrder->id)
        ->assertSee('MARIA SOUZA')
        ->assertSee('ABC1D23')
        ->assertSee('TROCA DE ÓLEO')
        ->assertSee('Alinhamento')
        ->assertSee('- R$ 20,00')
        ->assertSee('R$ 100,00')
        ->assertSee('Assinatura do cliente')
        ->assertDontSee('CANCELADA');
});

test('a cancelled order is marked as cancelled on the print page', function () {
    $this->put(route('service-orders.cancel', $this->serviceOrder->id), [
        'cancel_reason' => 'Cliente desistiu do serviço',
        'cancel_confirm' => '1',
    ]);

    $this->get(route('service-orders.print', $this->serviceOrder->id))
        ->assertOk()
        ->assertSee('CANCELADA')
        ->assertSee('Cliente desistiu do serviço');
});

test('the order page links to the print page', function () {
    $this->get(route('service-orders.show', $this->serviceOrder->id))
        ->assertSee(route('service-orders.print', $this->serviceOrder->id));
});

test('an order from another tenant cannot be printed', function () {
    $this->actingAs(User::factory()->create());

    $this->get(route('service-orders.print', $this->serviceOrder->id))->assertNotFound();
});

test('guests cannot print orders', function () {
    auth()->logout();

    $this->get(route('service-orders.print', $this->serviceOrder->id))->assertRedirect(route('login'));
});
