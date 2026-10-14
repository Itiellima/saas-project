<?php

test('registration screen can be rendered', function () {
    $response = $this->get('/register');

    $response->assertStatus(200);
});

test('new users can register', function () {
    $response =$this->post('/register', [
        'tenant_name' => 'Test Tenant',
        'user_name' => 'Test User',
        'user_email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password'
    ]);

    $response->assertSessionHasNoErrors();
    $response->assertRedirect(route('dashboard', absolute: false));

    $this->assertDatabaseHas('tenants', [
        'name' => 'Test Tenant',
    ]);

    $this->assertDatabaseHas('users', [
        'name' => 'Test User',
        'email' => 'test@example.com',
    ]);


    $this->assertAuthenticated();
});
