<?php

use App\Models\ServiceOrder;
use App\Models\User;

beforeEach(function () {
    $this->actingAs(User::factory()->create());
});

test('the informed entry and exit date and time are saved', function () {
    $this->post(route('service-orders.store'), [
        'customer_name' => 'João',
        'vehicle_enter' => '2026-11-10T08:30',
        'vehicle_leave' => '2026-11-12T17:45',
    ])->assertSessionHasNoErrors();

    $serviceOrder = ServiceOrder::first();

    expect($serviceOrder->vehicle_enter->format('Y-m-d H:i'))->toBe('2026-11-10 08:30')
        ->and($serviceOrder->vehicle_leave->format('Y-m-d H:i'))->toBe('2026-11-12 17:45');
});

test('the entry date defaults to now when not informed', function () {
    $this->post(route('service-orders.store'), [
        'customer_name' => 'João',
    ])->assertSessionHasNoErrors();

    expect(ServiceOrder::first()->vehicle_enter)->not->toBeNull();
});

test('the exit cannot be before the entry', function () {
    $this->post(route('service-orders.store'), [
        'customer_name' => 'João',
        'vehicle_enter' => '2026-11-10T08:30',
        'vehicle_leave' => '2026-11-09T17:45',
    ])->assertSessionHasErrors('vehicle_leave');

    expect(ServiceOrder::count())->toBe(0);
});
