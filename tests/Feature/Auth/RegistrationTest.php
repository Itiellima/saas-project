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

function registrationData(array $overrides = []): array
{
    return [
        'tenant_name' => 'Oficina do João',
        'user_name' => 'João Silva',
        'user_email' => 'joao@example.com',
        'password' => 'senha-segura-123',
        'password_confirmation' => 'senha-segura-123',
        ...$overrides,
    ];
}

test('the first user of a workshop is its admin', function () {
    $this->post('/register', registrationData())->assertSessionHasNoErrors();

    expect(App\Models\User::where('email', 'joao@example.com')->value('role'))->toBe('admin');
});

test('an email already in use cannot register again', function () {
    $this->post('/register', registrationData());
    auth()->logout();

    $this->post('/register', registrationData(['tenant_name' => 'Outra Oficina']))
        ->assertSessionHasErrors('user_email');

    expect(App\Models\Tenant::count())->toBe(1);
});

test('two workshops with the same name can register', function () {
    $this->post('/register', registrationData())->assertSessionHasNoErrors();
    auth()->logout();

    $this->post('/register', registrationData(['user_email' => 'maria@example.com']))
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('dashboard', absolute: false));

    expect(App\Models\Tenant::where('name', 'Oficina do João')->pluck('domain')->all())
        ->toBe(['oficina-do-joao', 'oficina-do-joao-2']);
});

test('the password must be at least 8 characters', function () {
    $this->post('/register', registrationData(['password' => 'curta', 'password_confirmation' => 'curta']))
        ->assertSessionHasErrors('password');

    expect(App\Models\Tenant::count())->toBe(0);
});

test('a logged in user cannot open the registration page', function () {
    $this->actingAs(App\Models\User::factory()->create());

    $this->get('/register')->assertRedirect(route('dashboard', absolute: false));
    $this->post('/register', registrationData())->assertRedirect(route('dashboard', absolute: false));

    expect(App\Models\User::where('email', 'joao@example.com')->exists())->toBeFalse();
});
