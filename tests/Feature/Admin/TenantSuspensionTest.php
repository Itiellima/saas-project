<?php

use App\Models\Tenant;
use App\Models\User;
use Livewire\Volt\Volt;

test('a user from a suspended workshop can not log in', function () {
    $user = User::factory()->for(Tenant::factory()->suspended())->create();

    Volt::test('auth.login')
        ->set('email', $user->email)
        ->set('password', 'password')
        ->call('login')
        ->assertHasErrors(['email' => __('auth.suspended')]);

    $this->assertGuest();
});

test('a wrong password on a suspended workshop still says wrong credentials', function () {
    $user = User::factory()->for(Tenant::factory()->suspended())->create();

    Volt::test('auth.login')
        ->set('email', $user->email)
        ->set('password', 'senha-errada')
        ->call('login')
        ->assertHasErrors(['email' => __('auth.failed')]);
});

test('a logged in user is signed out on the next request after the workshop is suspended', function () {
    $user = User::factory()->create();

    $this->actingAs($user)->get(route('dashboard'))->assertOk();

    $user->tenant->update(['status' => 'suspended']);

    $this->get(route('dashboard'))
        ->assertRedirect(route('login'))
        ->assertSessionHas('suspended');

    $this->assertGuest();
});

test('the login page shows the suspension message', function () {
    $this->withSession(['suspended' => __('auth.suspended')])
        ->get(route('login'))
        ->assertSee(__('auth.suspended'));
});

test('users from active workshops are not affected', function () {
    $this->actingAs(User::factory()->create())
        ->get(route('dashboard'))
        ->assertOk();

    $this->assertAuthenticated();
});
