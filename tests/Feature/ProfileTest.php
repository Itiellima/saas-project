<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;

beforeEach(function () {
    $this->user = User::factory()->create([
        'name' => 'Maria Souza',
        'email' => 'maria@example.com',
    ]);

    $this->actingAs($this->user);
});

test('the profile page shows the user data', function () {
    $this->get(route('profile.edit'))
        ->assertOk()
        ->assertSee('Meu perfil')
        ->assertSee('value="Maria Souza"', false)
        ->assertSee('value="maria@example.com"', false);
});

test('guests are redirected to the login page', function () {
    auth()->logout();

    $this->get(route('profile.edit'))->assertRedirect(route('login'));
});

test('the name can be changed keeping the same email', function () {
    $this->put(route('profile.update'), ['name' => 'Maria da Silva', 'email' => 'maria@example.com'])
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('profile.edit'));

    expect($this->user->fresh()->name)->toBe('Maria da Silva');
});

test('the email cannot be one used by another user', function () {
    User::factory()->create(['email' => 'pedro@example.com']);

    $this->put(route('profile.update'), ['name' => 'Maria Souza', 'email' => 'pedro@example.com'])
        ->assertSessionHasErrors('email');

    expect($this->user->fresh()->email)->toBe('maria@example.com');
});

test('the password can be changed with the current password', function () {
    $this->put(route('profile.password'), [
        'current_password' => 'password',
        'password' => 'nova-senha-123',
        'password_confirmation' => 'nova-senha-123',
    ])->assertSessionHasNoErrors()->assertSessionHas('success');

    expect(Hash::check('nova-senha-123', $this->user->fresh()->password))->toBeTrue();

    expect(auth()->validate(['email' => 'maria@example.com', 'password' => 'nova-senha-123']))->toBeTrue()
        ->and(auth()->validate(['email' => 'maria@example.com', 'password' => 'password']))->toBeFalse();
});

test('the password is not changed with a wrong current password', function () {
    $this->put(route('profile.password'), [
        'current_password' => 'senha-errada',
        'password' => 'nova-senha-123',
        'password_confirmation' => 'nova-senha-123',
    ])->assertSessionHasErrors('current_password');

    expect(Hash::check('password', $this->user->fresh()->password))->toBeTrue();
});

test('the new password must be confirmed and long enough', function () {
    $this->put(route('profile.password'), [
        'current_password' => 'password',
        'password' => 'nova-senha-123',
        'password_confirmation' => 'outra-senha',
    ])->assertSessionHasErrors('password');

    $this->put(route('profile.password'), [
        'current_password' => 'password',
        'password' => 'curta',
        'password_confirmation' => 'curta',
    ])->assertSessionHasErrors('password');

    expect(Hash::check('password', $this->user->fresh()->password))->toBeTrue();
});

test('the old settings pages no longer exist', function () {
    $this->get('/settings/profile')->assertNotFound();
    $this->get('/settings/password')->assertNotFound();
});
