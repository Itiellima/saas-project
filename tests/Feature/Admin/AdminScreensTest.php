<?php

use App\Models\Item;
use App\Models\ServiceOrder;
use App\Models\Tenant;
use App\Models\User;

beforeEach(function () {
    $this->admin = User::factory()->superAdmin()->create();

    $this->workshop = Tenant::factory()->create(['name' => 'Auto Center Silva']);
    $this->owner = User::factory()->for($this->workshop)->create(['name' => 'José Silva', 'email' => 'jose@silva.test']);
    $this->owner->forceFill(['role' => 'admin'])->save();

    $this->actingAs($this->admin);
});

test('the admin dashboard counts workshops and orders of the month', function () {
    Tenant::factory()->suspended()->create();
    ServiceOrder::factory()->count(2)->create(['tenant_id' => $this->workshop->id]);
    ServiceOrder::factory()->create(['tenant_id' => $this->workshop->id, 'created_at' => now()->subMonthNoOverflow()->startOfMonth()]);

    $this->get(route('admin.dashboard'))
        ->assertOk()
        ->assertViewHas('stats', fn ($stats) => $stats['tenants'] === 3
            && $stats['active'] === 2
            && $stats['suspended'] === 1
            && $stats['orders_this_month'] === 2)
        ->assertSee('Auto Center Silva');
});

test('the workshop list shows owner, counts and last order', function () {
    ServiceOrder::factory()->count(3)->create(['tenant_id' => $this->workshop->id]);

    $this->get(route('admin.tenants.index'))
        ->assertOk()
        ->assertSee('Auto Center Silva')
        ->assertSee('jose@silva.test')
        ->assertViewHas('tenants', fn ($tenants) => $tenants->firstWhere('id', $this->workshop->id)->service_orders_count === 3);
});

test('the workshop list can be searched by owner e-mail and filtered by status', function () {
    Tenant::factory()->suspended()->create(['name' => 'Mecânica Parada']);

    $this->get(route('admin.tenants.index', ['search' => 'jose@silva']))
        ->assertSee('Auto Center Silva')
        ->assertDontSee('Mecânica Parada');

    $this->get(route('admin.tenants.index', ['status' => 'suspended']))
        ->assertSee('Mecânica Parada')
        ->assertDontSee('Auto Center Silva');

    // Busca + status juntos: o orWhere da busca não pode furar o filtro
    $this->get(route('admin.tenants.index', ['search' => 'a', 'status' => 'suspended']))
        ->assertDontSee('Auto Center Silva');
});

test('the workshop detail shows its users and numbers', function () {
    ServiceOrder::factory()->count(2)->create(['tenant_id' => $this->workshop->id]);
    Item::factory()->create(['tenant_id' => $this->workshop->id]);

    $this->get(route('admin.tenants.show', $this->workshop))
        ->assertOk()
        ->assertSee('José Silva')
        ->assertSee('jose@silva.test')
        ->assertViewHas('stats', fn ($stats) => $stats['orders'] === 2 && $stats['items'] === 1)
        ->assertSee('Suspender');
});

test('a workshop can be suspended and reactivated', function () {
    $this->put(route('admin.tenants.suspend', $this->workshop))->assertSessionHas('success');
    expect($this->workshop->fresh()->isSuspended())->toBeTrue();

    $this->put(route('admin.tenants.reactivate', $this->workshop))->assertSessionHas('success');
    expect($this->workshop->fresh()->isSuspended())->toBeFalse();
});

test('the super admin workshop can not be suspended, not even by a direct request', function () {
    $this->get(route('admin.tenants.show', $this->admin->tenant))
        ->assertOk()
        ->assertDontSee('Suspender')
        ->assertSee('Oficina do administrador geral');

    $this->put(route('admin.tenants.suspend', $this->admin->tenant))->assertSessionHas('error');

    expect($this->admin->tenant->fresh()->isSuspended())->toBeFalse();
});

test('admin screens never show service order contents', function () {
    ServiceOrder::factory()->create([
        'tenant_id' => $this->workshop->id,
        'customer_name' => 'CLIENTE SIGILOSO',
        'vehicle_plate' => 'SIG1L05',
        'total' => 98765.43,
    ]);

    foreach ([
        route('admin.dashboard'),
        route('admin.tenants.index'),
        route('admin.tenants.show', $this->workshop),
    ] as $url) {
        $this->get($url)
            ->assertOk()
            ->assertDontSee('CLIENTE SIGILOSO')
            ->assertDontSee('SIG1L05')
            ->assertDontSee('98.765,43');
    }
});

test('regular users get not found on every admin screen', function () {
    $this->actingAs($this->owner);

    $this->get(route('admin.tenants.index'))->assertNotFound();
    $this->get(route('admin.tenants.show', $this->workshop))->assertNotFound();
    $this->put(route('admin.tenants.suspend', $this->workshop))->assertNotFound();
    $this->put(route('admin.tenants.reactivate', $this->workshop))->assertNotFound();

    expect($this->workshop->fresh()->isSuspended())->toBeFalse();
});

test('only the super admin sees the admin link in the menu', function () {
    $this->get(route('dashboard'))->assertSee(route('admin.dashboard'));

    $this->actingAs($this->owner)
        ->get(route('dashboard'))
        ->assertDontSee(route('admin.dashboard'));
});
