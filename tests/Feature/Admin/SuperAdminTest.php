<?php

use App\Models\User;

test('the super admin can open the admin area', function () {
    $this->actingAs(User::factory()->superAdmin()->create())
        ->get(route('admin.dashboard'))
        ->assertOk()
        ->assertSee('Administração');
});

test('a regular workshop owner gets not found in the admin area', function () {
    $owner = User::factory()->create();
    $owner->forceFill(['role' => 'admin'])->save();

    $this->actingAs($owner)->get(route('admin.dashboard'))->assertNotFound();
});

test('guests are sent to the login page from the admin area', function () {
    $this->get(route('admin.dashboard'))->assertRedirect(route('login'));
});

test('is_super_admin can not be mass assigned', function () {
    $user = User::factory()->create();

    $user->update(['is_super_admin' => true]);

    expect($user->fresh()->isSuperAdmin())->toBeFalse();
});

test('the command grants and removes super admin access', function () {
    $user = User::factory()->create(['email' => 'dono@oficina.test']);

    $this->artisan('app:make-super-admin', ['email' => 'dono@oficina.test'])
        ->expectsOutputToContain('agora é administrador geral')
        ->assertSuccessful();

    expect($user->fresh()->isSuperAdmin())->toBeTrue();

    $this->artisan('app:make-super-admin', ['email' => 'dono@oficina.test', '--remove' => true])
        ->assertSuccessful();

    expect($user->fresh()->isSuperAdmin())->toBeFalse();
});

test('the command fails for an unknown e-mail and creates nothing', function () {
    $this->artisan('app:make-super-admin', ['email' => 'ninguem@oficina.test'])
        ->expectsOutputToContain('Nenhum usuário')
        ->assertFailed();

    expect(User::count())->toBe(0);
});

test('the super admin workshop can not be suspended', function () {
    $admin = User::factory()->superAdmin()->create();
    $regular = User::factory()->create();

    expect($admin->tenant->canBeSuspended())->toBeFalse()
        ->and($regular->tenant->canBeSuspended())->toBeTrue();
});
