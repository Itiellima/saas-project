<?php

use App\Models\Item;
use App\Models\ServiceOrder;
use App\Models\User;

test('guests are redirected to the login page', function () {
    $response = $this->get('/dashboard');
    $response->assertRedirect('/login');
});

test('authenticated users can visit the dashboard', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->get('/dashboard');
    $response->assertStatus(200);
});

test('an empty dashboard shows zeros and empty messages', function () {
    $this->actingAs(User::factory()->create());

    $this->get(route('dashboard'))
        ->assertOk()
        ->assertViewHas('stats', fn ($stats) => $stats['pending'] === 0 && $stats['revenue'] === 0.0)
        ->assertSee('Nenhuma OS em aberto')
        ->assertSee('Nenhum produto com estoque baixo')
        ->assertSee('Nenhuma OS cadastrada ainda');
});

test('the dashboard shows the monthly indicators', function () {
    $user = User::factory()->create();
    $order = fn (array $attributes) => ServiceOrder::factory()->create(['tenant_id' => $user->tenant_id, ...$attributes]);

    $order(['status' => 'open']);
    $order(['status' => 'open']);
    $order(['status' => 'in_progress']);
    $order(['status' => 'finished', 'finished_at' => now(), 'total' => 300]);
    $order(['status' => 'finished', 'finished_at' => now(), 'total' => 100]);
    $order(['status' => 'finished', 'finished_at' => now()->subMonthNoOverflow(), 'total' => 999]); // mês passado
    $order(['status' => 'cancelled', 'total' => 500]);

    $this->actingAs($user)
        ->get(route('dashboard'))
        ->assertOk()
        ->assertViewHas('stats', function ($stats) {
            return $stats['open'] === 2
                && $stats['in_progress'] === 1
                && $stats['pending'] === 3
                && $stats['finished'] === 2
                && $stats['revenue'] === 400.0
                && $stats['average_ticket'] === 200.0;
        })
        ->assertSee('R$ 400,00')
        ->assertSee('R$ 200,00');
});

test('the oldest pending orders are listed first', function () {
    $user = User::factory()->create();

    $newer = ServiceOrder::factory()->create(['tenant_id' => $user->tenant_id, 'vehicle_enter' => now()->subDay()]);
    $older = ServiceOrder::factory()->create(['tenant_id' => $user->tenant_id, 'vehicle_enter' => now()->subDays(10)]);
    ServiceOrder::factory()->create(['tenant_id' => $user->tenant_id, 'status' => 'finished']);

    $this->actingAs($user)
        ->get(route('dashboard'))
        ->assertViewHas('oldestPending', fn ($orders) => $orders->pluck('id')->all() === [$older->id, $newer->id]);
});

test('only products with low stock are listed', function () {
    $user = User::factory()->create();

    Item::factory()->create(['tenant_id' => $user->tenant_id, 'name' => 'Filtro de óleo', 'stock' => 2]);
    Item::factory()->create(['tenant_id' => $user->tenant_id, 'name' => 'Pastilha de freio', 'stock' => 50]);
    Item::factory()->service()->create(['tenant_id' => $user->tenant_id, 'name' => 'Alinhamento']);

    $this->actingAs($user)
        ->get(route('dashboard'))
        ->assertSee('Filtro de óleo')
        ->assertDontSee('Pastilha de freio')
        ->assertDontSee('Alinhamento');
});

test('data from other tenants is not shown', function () {
    $user = User::factory()->create();
    $other = User::factory()->create();

    ServiceOrder::factory()->create([
        'tenant_id' => $other->tenant_id,
        'customer_name' => 'CLIENTE DE OUTRA OFICINA',
        'status' => 'finished',
        'finished_at' => now(),
        'total' => 1000,
    ]);
    Item::factory()->create(['tenant_id' => $other->tenant_id, 'name' => 'Peça de outra oficina', 'stock' => 0]);

    $this->actingAs($user)
        ->get(route('dashboard'))
        ->assertViewHas('stats', fn ($stats) => $stats['finished'] === 0 && $stats['revenue'] === 0.0)
        ->assertDontSee('CLIENTE DE OUTRA OFICINA')
        ->assertDontSee('Peça de outra oficina');
});
