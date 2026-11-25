<?php

use App\Models\ServiceOrder;
use App\Models\User;

beforeEach(function () {
    $this->owner = User::factory()->create(['name' => 'Carlos Dono']);
    $this->employee = User::factory()->employee()->create([
        'tenant_id' => $this->owner->tenant_id,
        'name' => 'João Mecânico',
    ]);
});

test('creating a service order records who opened it', function () {
    $this->actingAs($this->employee)->post(route('service-orders.store'), [
        'customer_name' => 'Maria',
        'vehicle_plate' => 'ABC1D23',
    ]);

    expect(ServiceOrder::first()->created_by)->toBe($this->employee->id);
});

test('finishing records who finished it and reopening clears it', function () {
    $order = ServiceOrder::factory()->create(['tenant_id' => $this->owner->tenant_id, 'status' => 'open']);

    $this->actingAs($this->employee)->put(route('service-orders.close', $order->id));
    expect($order->fresh()->finished_by)->toBe($this->employee->id);

    $this->actingAs($this->owner)->put(route('service-orders.reopen', $order->id), [
        'reopen_reason' => 'Faltou lançar um item.',
        'reopen_confirm' => '1',
    ]);
    expect($order->fresh()->finished_by)->toBeNull();

    // Finalizada de novo: vale quem finalizou por último
    $this->put(route('service-orders.close', $order->id));
    expect($order->fresh()->finished_by)->toBe($this->owner->id);
});

test('the service order page and the print show who opened and finished it', function () {
    $order = ServiceOrder::factory()->create([
        'tenant_id' => $this->owner->tenant_id,
        'status' => 'finished',
        'finished_at' => now(),
        'created_by' => $this->employee->id,
        'finished_by' => $this->owner->id,
    ]);

    $this->actingAs($this->owner);

    $this->get(route('service-orders.show', $order->id))
        ->assertSee('Aberta por João Mecânico · Finalizada por Carlos Dono');

    $this->get(route('service-orders.print', $order->id))
        ->assertSee('Aberta por:')
        ->assertSee('João Mecânico')
        ->assertSee('Finalizada por:')
        ->assertSee('Carlos Dono');
});

test('a deactivated employee name stays in the history', function () {
    $order = ServiceOrder::factory()->create([
        'tenant_id' => $this->owner->tenant_id,
        'created_by' => $this->employee->id,
    ]);
    $this->employee->forceFill(['active' => false])->save();

    $this->actingAs($this->owner)
        ->get(route('service-orders.show', $order->id))
        ->assertSee('Aberta por João Mecânico');
});

test('old service orders without authorship still render normally', function () {
    $order = ServiceOrder::factory()->create(['tenant_id' => $this->owner->tenant_id]);

    $this->actingAs($this->owner)
        ->get(route('service-orders.show', $order->id))
        ->assertOk()
        ->assertSee('Detalhes e gerenciamento da ordem de serviço')
        ->assertDontSee('Aberta por');

    $this->get(route('service-orders.print', $order->id))
        ->assertOk()
        ->assertDontSee('Aberta por:');
});

test('the request can not forge who opened the service order', function () {
    $this->actingAs($this->employee)->post(route('service-orders.store'), [
        'customer_name' => 'Maria',
        'created_by' => $this->owner->id,
    ]);

    expect(ServiceOrder::first()->created_by)->toBe($this->employee->id);
});
