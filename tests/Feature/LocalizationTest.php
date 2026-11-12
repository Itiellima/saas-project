<?php

use App\Models\User;

test('the application speaks portuguese', function () {
    expect(app()->getLocale())->toBe('pt_BR');
});

test('validation messages are in portuguese with readable field names', function () {
    $this->actingAs(User::factory()->create());

    $this->post(route('service-orders.store'), [])
        ->assertSessionHasErrors(['customer_name' => 'O campo nome do cliente é obrigatório.']);
});

test('success messages are in portuguese', function () {
    $this->actingAs(User::factory()->create());

    $this->post(route('service-orders.store'), ['customer_name' => 'João'])
        ->assertSessionHas('success', 'Ordem de serviço criada com sucesso.');
});
