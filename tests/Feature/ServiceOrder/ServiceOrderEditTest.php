<?php

use App\Models\ServiceOrder;
use App\Models\User;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->serviceOrder = ServiceOrder::factory()->create([
        'tenant_id' => $this->user->tenant_id,
        'customer_name' => 'JOAO SILVA',
        'vehicle_plate' => 'ABC1D23',
        'vehicle_enter' => '2026-11-10 08:30',
    ]);

    $this->actingAs($this->user);
});

function validData(array $overrides = []): array
{
    return [
        'customer_name' => 'JOÃO DA SILVA',
        'customer_phone' => '(11) 99999-0000',
        'vehicle_plate' => 'XYZ9K88',
        'vehicle_model' => 'COROLLA',
        'vehicle_km' => 85000,
        'vehicle_enter' => '2026-11-10T08:30',
        'vehicle_leave' => '',
        'description' => 'TROCA DE ÓLEO',
        ...$overrides,
    ];
}

test('the edit form shows the current data', function () {
    $this->get(route('service-orders.edit', $this->serviceOrder->id))
        ->assertOk()
        ->assertSee('Editar OS #' . $this->serviceOrder->id)
        ->assertSee('value="JOAO SILVA"', false)
        ->assertSee('value="2026-11-10T08:30"', false);
});

test('a service order can be edited', function () {
    $this->put(route('service-orders.update', $this->serviceOrder->id), validData())
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('service-orders.show', $this->serviceOrder->id));

    $serviceOrder = $this->serviceOrder->fresh();

    expect($serviceOrder->customer_name)->toBe('JOÃO DA SILVA')
        ->and($serviceOrder->vehicle_plate)->toBe('XYZ9K88')
        ->and($serviceOrder->vehicle_km)->toBe(85000)
        ->and($serviceOrder->vehicle_leave)->toBeNull();
});

test('clearing the entry date keeps the previous one', function () {
    $this->put(route('service-orders.update', $this->serviceOrder->id), validData(['vehicle_enter' => '']))
        ->assertSessionHasNoErrors();

    expect($this->serviceOrder->fresh()->vehicle_enter->format('Y-m-d H:i'))->toBe('2026-11-10 08:30');
});

test('editing uses the same validation as creating', function () {
    $this->put(route('service-orders.update', $this->serviceOrder->id), validData([
        'customer_name' => '',
        'vehicle_leave' => '2026-11-09T10:00',
    ]))->assertSessionHasErrors(['customer_name', 'vehicle_leave']);

    expect($this->serviceOrder->fresh()->customer_name)->toBe('JOAO SILVA');
});

test('editing does not change status or total', function () {
    $this->put(route('service-orders.update', $this->serviceOrder->id), validData([
        'status' => 'finished',
        'total' => 9999,
    ]));

    $serviceOrder = $this->serviceOrder->fresh();

    expect($serviceOrder->status)->toBe('open')
        ->and((float) $serviceOrder->total)->toBe(0.0);
});

test('a finished service order cannot be edited', function () {
    $this->put(route('service-orders.close', $this->serviceOrder->id));

    $this->get(route('service-orders.edit', $this->serviceOrder->id))
        ->assertRedirect(route('service-orders.show', $this->serviceOrder->id))
        ->assertSessionHas('error');

    $this->put(route('service-orders.update', $this->serviceOrder->id), validData())
        ->assertSessionHas('error');

    expect($this->serviceOrder->fresh()->customer_name)->toBe('JOAO SILVA');
});

test('a service order from another tenant cannot be edited', function () {
    $this->actingAs(User::factory()->create());

    $this->get(route('service-orders.edit', $this->serviceOrder->id))->assertNotFound();
    $this->put(route('service-orders.update', $this->serviceOrder->id), validData())->assertNotFound();

    expect($this->serviceOrder->fresh()->customer_name)->toBe('JOAO SILVA');
});

test('finishing fills an empty exit date with now', function () {
    $this->put(route('service-orders.close', $this->serviceOrder->id));

    expect($this->serviceOrder->fresh()->vehicle_leave)->not->toBeNull();
});

test('finishing keeps an exit date that was already informed', function () {
    $this->serviceOrder->update(['vehicle_leave' => '2026-11-11 17:00']);

    $this->put(route('service-orders.close', $this->serviceOrder->id));

    expect($this->serviceOrder->fresh()->vehicle_leave->format('Y-m-d H:i'))->toBe('2026-11-11 17:00');
});

test('the edit button is hidden on finished orders', function () {
    $this->get(route('service-orders.show', $this->serviceOrder->id))
        ->assertSee(route('service-orders.edit', $this->serviceOrder->id));

    $this->put(route('service-orders.close', $this->serviceOrder->id));

    $this->get(route('service-orders.show', $this->serviceOrder->id))
        ->assertDontSee(route('service-orders.edit', $this->serviceOrder->id));
});
