<?php

use App\Models\ServiceOrder;
use App\Models\User;
use Livewire\Volt\Volt;

beforeEach(function () {
    $this->owner = User::factory()->create();
    $this->employee = User::factory()->employee()->create([
        'tenant_id' => $this->owner->tenant_id,
        'name' => 'João Mecânico',
    ]);

    $this->actingAs($this->owner);
});

test('the owner deactivates and reactivates an employee', function () {
    $this->put(route('team.toggle', $this->employee->id))->assertSessionHas('success');
    expect($this->employee->fresh()->isActive())->toBeFalse();

    $this->get(route('team.index'))->assertSee('Desativado')->assertSee('Reativar');

    $this->put(route('team.toggle', $this->employee->id));
    expect($this->employee->fresh()->isActive())->toBeTrue();
});

test('a deactivated employee can not log in, with the right message', function () {
    $this->employee->forceFill(['active' => false])->save();
    auth()->logout();

    Volt::test('auth.login')
        ->set('email', $this->employee->email)
        ->set('password', 'password')
        ->call('login')
        ->assertHasErrors(['email' => __('auth.deactivated')]);

    $this->assertGuest();
});

test('a wrong password on a deactivated account still says wrong credentials', function () {
    $this->employee->forceFill(['active' => false])->save();
    auth()->logout();

    Volt::test('auth.login')
        ->set('email', $this->employee->email)
        ->set('password', 'senha-errada')
        ->call('login')
        ->assertHasErrors(['email' => __('auth.failed')]);
});

test('a logged in employee is signed out on the next request after being deactivated', function () {
    $this->actingAs($this->employee)->get(route('dashboard'))->assertOk();

    $this->employee->forceFill(['active' => false])->save();
    $this->employee->refresh();

    $this->get(route('dashboard'))
        ->assertRedirect(route('login'))
        ->assertSessionHas('suspended', __('auth.deactivated'));

    $this->assertGuest();
});

test('owners, the owner himself and other workshops users can not be deactivated', function () {
    $partner = User::factory()->create(['tenant_id' => $this->owner->tenant_id]);
    $stranger = User::factory()->employee()->create();

    $this->put(route('team.toggle', $this->owner->id))->assertNotFound();
    $this->put(route('team.toggle', $partner->id))->assertNotFound();
    $this->put(route('team.toggle', $stranger->id))->assertNotFound();

    expect($this->owner->fresh()->isActive())->toBeTrue()
        ->and($partner->fresh()->isActive())->toBeTrue()
        ->and($stranger->fresh()->isActive())->toBeTrue();
});

test('an employee can not deactivate anyone', function () {
    $colleague = User::factory()->employee()->create(['tenant_id' => $this->owner->tenant_id]);

    $this->actingAs($this->employee)
        ->put(route('team.toggle', $colleague->id))
        ->assertNotFound();

    expect($colleague->fresh()->isActive())->toBeTrue();
});

test('deactivating keeps the user and the workshop history', function () {
    $order = ServiceOrder::factory()->create(['tenant_id' => $this->owner->tenant_id]);

    $this->put(route('team.toggle', $this->employee->id));

    expect(User::find($this->employee->id))->not->toBeNull()
        ->and($order->fresh())->not->toBeNull();
});

test('the admin sees deactivated users in the workshop detail', function () {
    $this->employee->forceFill(['active' => false])->save();

    $this->actingAs(User::factory()->superAdmin()->create())
        ->get(route('admin.tenants.show', $this->owner->tenant_id))
        ->assertSee('João Mecânico')
        ->assertSee('Desativado');
});
