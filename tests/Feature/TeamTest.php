<?php

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Livewire\Volt\Volt;

beforeEach(function () {
    $this->owner = User::factory()->create(['name' => 'Dono da Oficina']);
    $this->owner->forceFill(['role' => 'admin'])->save();

    $this->actingAs($this->owner);
});

function addEmployee(array $data = []): array
{
    test()->post(route('team.store'), array_merge(['name' => 'João Mecânico', 'email' => 'joao@oficina.test'], $data));

    return session('temporary_password') ?? [];
}

test('the owner sees the team page and the menu link', function () {
    $this->get(route('team.index'))
        ->assertOk()
        ->assertSee('Dono da Oficina')
        ->assertSee('Adicionar funcionário');

    $this->get(route('dashboard'))->assertSee(route('team.index'));
});

test('the owner adds an employee with a temporary password shown once', function () {
    $this->post(route('team.store'), ['name' => 'João Mecânico', 'email' => 'joao@oficina.test'])
        ->assertRedirect(route('team.index'))
        ->assertSessionHas('temporary_password');

    $credentials = session('temporary_password');
    $employee = User::where('email', 'joao@oficina.test')->first();

    expect($employee->tenant_id)->toBe($this->owner->tenant_id)
        ->and($employee->role)->toBe('user')
        ->and($employee->must_change_password)->toBeTrue()
        ->and(strlen($credentials['password']))->toBe(10)
        ->and(Hash::check($credentials['password'], $employee->password))->toBeTrue();

    // Na tela seguinte a senha aparece; depois disso, não mais
    $this->get(route('team.index'))->assertSee($credentials['password']);
    $this->get(route('team.index'))->assertDontSee($credentials['password']);
});

test('an e-mail already used anywhere in the system is rejected', function () {
    User::factory()->create(['email' => 'ocupado@outra.test']);

    $this->post(route('team.store'), ['name' => 'Alguém', 'email' => 'ocupado@outra.test'])
        ->assertSessionHasErrors('email');
});

test('the request can not make the employee an owner', function () {
    $this->post(route('team.store'), ['name' => 'Esperto', 'email' => 'esperto@oficina.test', 'role' => 'admin']);

    expect(User::where('email', 'esperto@oficina.test')->value('role'))->toBe('user');
});

test('an employee with a temporary password can only reach the password change page', function () {
    $credentials = addEmployee();
    auth()->logout();

    Volt::test('auth.login')
        ->set('email', 'joao@oficina.test')
        ->set('password', $credentials['password'])
        ->call('login');

    $this->get(route('dashboard'))->assertRedirect(route('password.first'));
    $this->get(route('service-orders.index'))->assertRedirect(route('password.first'));
    $this->get(route('password.first'))->assertOk()->assertSee('Defina sua senha');
});

test('the employee sets a new password and then uses the system normally', function () {
    addEmployee();
    $employee = User::where('email', 'joao@oficina.test')->first();
    $this->actingAs($employee);

    $this->put(route('password.first.update'), [
        'password' => 'MinhaSenha123',
        'password_confirmation' => 'MinhaSenha123',
    ])->assertRedirect(route('dashboard'));

    $employee->refresh();

    expect($employee->must_change_password)->toBeFalse()
        ->and(Hash::check('MinhaSenha123', $employee->password))->toBeTrue();

    $this->get(route('dashboard'))->assertOk();
    $this->get(route('password.first'))->assertRedirect(route('dashboard'));
});

test('the new password must be different from the temporary one', function () {
    $credentials = addEmployee();
    $this->actingAs(User::where('email', 'joao@oficina.test')->first());

    $this->put(route('password.first.update'), [
        'password' => $credentials['password'],
        'password_confirmation' => $credentials['password'],
    ])->assertSessionHasErrors('password');
});

test('the owner can generate a new temporary password for an employee', function () {
    addEmployee();
    $employee = User::where('email', 'joao@oficina.test')->first();
    $employee->forceFill(['must_change_password' => false])->save();

    $this->put(route('team.reset-password', $employee->id))->assertSessionHas('temporary_password');

    $employee->refresh();
    expect($employee->must_change_password)->toBeTrue()
        ->and(Hash::check(session('temporary_password')['password'], $employee->password))->toBeTrue();
});

test('the owner can not reset the password of another workshop user or of an owner', function () {
    $stranger = User::factory()->create();

    $this->put(route('team.reset-password', $stranger->id))->assertNotFound();
    $this->put(route('team.reset-password', $this->owner->id))->assertNotFound();
});

test('the team list only shows people from the own workshop', function () {
    User::factory()->create(['name' => 'Pessoa de Fora']);

    $this->get(route('team.index'))->assertDontSee('Pessoa de Fora');
});

test('employees can not open the team page nor see its menu link', function () {
    addEmployee();
    $employee = User::where('email', 'joao@oficina.test')->first();
    $employee->forceFill(['must_change_password' => false])->save();

    $this->actingAs($employee);

    $this->get(route('team.index'))->assertNotFound();
    $this->post(route('team.store'), ['name' => 'X', 'email' => 'x@oficina.test'])->assertNotFound();
    $this->get(route('dashboard'))->assertDontSee(route('team.index'));
});

test('resetting the password by e-mail also clears the temporary password flag', function () {
    Notification::fake();
    addEmployee();
    $employee = User::where('email', 'joao@oficina.test')->first();
    auth()->logout();

    Volt::test('auth.forgot-password')->set('email', $employee->email)->call('sendPasswordResetLink');

    Notification::assertSentTo($employee, ResetPassword::class, function ($notification) use ($employee) {
        Volt::test('auth.reset-password', ['token' => $notification->token])
            ->set('email', $employee->email)
            ->set('password', 'NovaSenha123')
            ->set('password_confirmation', 'NovaSenha123')
            ->call('resetPassword');

        return true;
    });

    expect($employee->fresh()->must_change_password)->toBeFalse();
});
