<?php

use App\Models\Item;
use App\Models\ServiceOrder;
use App\Models\Tenant;
use App\Models\User;
use Database\Seeders\DemoSeeder;

test('the demo seeder creates a consistent workshop', function () {
    $this->seed(DemoSeeder::class);

    $admin = User::where('email', 'admin@oficina.test')->first();

    expect($admin)->not->toBeNull()
        ->and(auth()->validate(['email' => 'admin@oficina.test', 'password' => 'password']))->toBeTrue()
        ->and(ServiceOrder::where('status', 'cancelled')->count())->toBe(1)
        ->and(ServiceOrder::whereNotNull('reopened_at')->count())->toBe(1)
        // Estoque: 8 - 3 OS com filtro de óleo; velas da OS cancelada voltaram
        ->and(Item::where('name', 'Filtro de óleo')->value('stock'))->toBe(5)
        ->and(Item::where('name', 'Vela de ignição')->value('stock'))->toBe(16);

    $this->actingAs($admin)
        ->get(route('dashboard'))
        ->assertOk()
        ->assertViewHas('stats', fn ($stats) => $stats['open'] === 1
            && $stats['in_progress'] === 3
            && $stats['finished'] === 2
            && $stats['revenue'] === 490.0
            && $stats['average_ticket'] === 245.0)
        ->assertSee('Filtro de ar')
        ->assertDontSee('Lâmpada H4');
});

test('running the demo seeder again recreates the demo workshop without touching others', function () {
    $other = User::factory()->create();

    $this->seed(DemoSeeder::class);
    $this->seed(DemoSeeder::class);

    expect(Tenant::where('domain', DemoSeeder::DOMAIN)->count())->toBe(1)
        ->and(User::where('email', 'admin@oficina.test')->count())->toBe(1)
        ->and(ServiceOrder::where('tenant_id', '!=', $other->tenant_id)->count())->toBe(8)
        ->and(Item::where('name', 'Filtro de óleo')->value('stock'))->toBe(5)
        ->and($other->fresh())->not->toBeNull();
});

test('the demo seeder refuses to run in production, even when called directly', function () {
    app()->detectEnvironment(fn () => 'production');

    // Chamada direta: o db:seed em produção pararia antes, pedindo confirmação
    (new DemoSeeder)->run();

    expect(Tenant::where('domain', DemoSeeder::DOMAIN)->exists())->toBeFalse();
});
