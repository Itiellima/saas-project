<?php

use App\Livewire\ServiceOrder\ItemForm;
use App\Livewire\ServiceOrder\ItemList;
use App\Models\Item;
use App\Models\ServiceOrder;
use App\Models\User;
use Livewire\Livewire;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->user->forceFill(['role' => 'admin'])->save();

    $this->actingAs($this->user);
});

test('a success message shows as a notification balloon', function () {
    $this->withSession(['success' => 'Oficina atualizada com sucesso.'])
        ->get(route('dashboard'))
        ->assertOk()
        ->assertSee('app-toast-success', false)
        ->assertSee('Oficina atualizada com sucesso.')
        ->assertDontSee('<div class="alert alert-success">', false);
});

test('an error message shows as an error balloon', function () {
    $this->withSession(['error' => 'Não foi possível concluir.'])
        ->get(route('dashboard'))
        ->assertSee('app-toast-error', false)
        ->assertSee('Não foi possível concluir.');
});

test('no balloon is shown when there is nothing to say', function () {
    // Só balões vindos do servidor têm data-bs-delay (o <template> do navegador não tem)
    $this->get(route('dashboard'))
        ->assertDontSee('data-bs-delay', false);
});

test('validation errors show one summary balloon and keep the fields highlighted', function () {
    $this->followingRedirects()
        ->from(route('workshop.edit'))
        ->put(route('workshop.update'), ['name' => '', 'state' => 'XX'])
        ->assertSee('Não foi possível salvar')
        ->assertSee('Confira os campos destacados.')
        ->assertSee('is-invalid', false);
});

test('adding an item to a service order dispatches a balloon', function () {
    $order = ServiceOrder::factory()->create(['tenant_id' => $this->user->tenant_id]);
    $service = Item::factory()->service()->create(['tenant_id' => $this->user->tenant_id]);

    Livewire::test(ItemForm::class, ['serviceOrder' => $order])
        ->set('item_id', $service->id)
        ->call('save')
        ->assertDispatched('toast', type: 'success', message: 'Item adicionado à OS.');
});

test('removing an item from a service order dispatches a balloon', function () {
    $order = ServiceOrder::factory()->create(['tenant_id' => $this->user->tenant_id]);
    $service = Item::factory()->service()->create(['tenant_id' => $this->user->tenant_id]);

    Livewire::test(ItemForm::class, ['serviceOrder' => $order])
        ->set('item_id', $service->id)
        ->call('save');

    Livewire::test(ItemList::class, ['serviceOrder' => $order])
        ->call('deleteItemList', $order->items()->first()->id)
        ->assertDispatched('toast', type: 'success', message: 'Item removido da OS.');
});
