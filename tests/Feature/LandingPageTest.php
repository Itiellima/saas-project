<?php

use App\Models\User;

test('visitors see the landing page with sign up and login', function () {
    $this->get(route('home'))
        ->assertOk()
        ->assertSee('Gestão simples para a sua')
        ->assertSee('Comece grátis')
        ->assertSee(route('register'))
        ->assertSee(route('login'))
        ->assertDontSee('Ir para o painel')
        ->assertDontSee('Laravel');
});

test('logged in users are offered to go to the dashboard', function () {
    $this->actingAs(User::factory()->create())
        ->get(route('home'))
        ->assertOk()
        ->assertSee('Ir para o painel')
        ->assertSee(route('dashboard'))
        ->assertDontSee('Comece grátis');
});

test('every page uses the OficinaOS favicon', function () {
    $favicon = asset('favicon.svg');

    $this->get(route('home'))->assertSee($favicon);
    $this->get(route('login'))->assertSee($favicon)->assertDontSee('Laravel');
    $this->get(route('register'))->assertSee($favicon);

    $this->actingAs(User::factory()->create())
        ->get(route('dashboard'))
        ->assertSee($favicon);

    expect(file_exists(public_path('favicon.svg')))->toBeTrue()
        ->and(file_exists(public_path('favicon.ico')))->toBeFalse();
});
