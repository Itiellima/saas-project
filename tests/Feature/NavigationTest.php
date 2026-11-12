<?php

use App\Livewire\ServiceOrder\Index;
use App\Models\ServiceOrder;
use App\Models\User;
use Livewire\Livewire;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
});

test('the menu links to the MVP pages and the profile', function () {
    $this->get(route('dashboard'))
        ->assertOk()
        ->assertSee(route('service-orders.index'))
        ->assertSee(route('items.index'))
        ->assertSee(route('profile.edit'))
        ->assertDontSee(route('vehicles.create'))
        ->assertDontSee(route('customers.index'))
        ->assertDontSee('Configurações');
});

test('the menu highlights the current page', function () {
    $html = $this->get(route('items.index'))->assertOk()->getContent();

    expect($html)->toMatch('/href="' . preg_quote(route('items.index'), '/') . '"\s+class="[^"]*active/')
        ->and($html)->not->toMatch('/href="' . preg_quote(route('dashboard'), '/') . '"\s+class="[^"]*active/');
});

test('the top bar shows the page title', function () {
    $this->get(route('items.index'))
        ->assertOk()
        ->assertSeeInOrder(['<h5 class="mb-0 fw-semibold">', 'Itens e Serviços'], false);
});

test('the search in the url filters the service orders', function () {
    ServiceOrder::factory()->create(['tenant_id' => $this->user->tenant_id, 'customer_name' => 'Maria Souza']);
    ServiceOrder::factory()->create(['tenant_id' => $this->user->tenant_id, 'customer_name' => 'Pedro Lima']);

    Livewire::withQueryParams(['search' => 'Maria'])
        ->test(Index::class)
        ->assertSee('Maria Souza')
        ->assertDontSee('Pedro Lima');
});
