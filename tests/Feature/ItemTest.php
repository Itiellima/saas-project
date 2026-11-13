<?php

use App\Livewire\ServiceOrder\ItemForm;
use App\Models\Item;
use App\Models\ServiceOrder;
use App\Models\StockMovement;
use App\Models\User;
use Livewire\Livewire;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
});

function itemData(array $overrides = []): array
{
    return [
        'name' => 'Filtro de óleo',
        'type' => 'product',
        'stock' => 10,
        'description' => 'Filtro para motor 1.0',
        'cost_price' => 20,
        'sale_price' => 35.5,
        ...$overrides,
    ];
}

function useInServiceOrder(Item $item): ServiceOrder
{
    $serviceOrder = ServiceOrder::factory()->create(['tenant_id' => $item->tenant_id]);

    Livewire::test(ItemForm::class, ['serviceOrder' => $serviceOrder])
        ->set('item_id', $item->id)
        ->call('save')
        ->assertHasNoErrors();

    return $serviceOrder;
}

// Cadastro e edição

test('an item can be created', function () {
    $this->post(route('items.store'), itemData())
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('items.index'));

    $item = Item::first();

    expect($item->tenant_id)->toBe($this->user->tenant_id)
        ->and($item->name)->toBe('Filtro de óleo')
        ->and((float) $item->sale_price)->toBe(35.5)
        ->and($item->fresh()->active)->toBeTrue();
});

test('empty numeric fields are saved as zero', function () {
    $this->post(route('items.store'), itemData(['stock' => '', 'cost_price' => '', 'sale_price' => '']))
        ->assertSessionHasNoErrors();

    $item = Item::first();
    $this->put(route('items.update', $item->id), itemData(['stock' => '']))->assertSessionHasNoErrors();

    expect($item->fresh()->stock)->toBe(0);
});

test('prices and stock cannot be negative', function () {
    $this->post(route('items.store'), itemData(['stock' => -1, 'cost_price' => -5, 'sale_price' => -50]))
        ->assertSessionHasErrors(['stock', 'cost_price', 'sale_price']);

    expect(Item::count())->toBe(0);
});

test('an item can be edited', function () {
    $item = Item::factory()->create(['tenant_id' => $this->user->tenant_id]);

    $this->put(route('items.update', $item->id), itemData(['name' => 'Filtro de ar', 'sale_price' => 42]))
        ->assertSessionHasNoErrors();

    expect($item->fresh()->name)->toBe('Filtro de ar')
        ->and((float) $item->fresh()->sale_price)->toBe(42.0);
});

// Excluir e desativar

test('an item never used can be deleted', function () {
    $item = Item::factory()->create(['tenant_id' => $this->user->tenant_id]);

    $this->delete(route('items.destroy', $item->id))->assertSessionHas('success');

    expect(Item::find($item->id))->toBeNull();
});

test('an item used in a service order cannot be deleted and keeps its history', function () {
    $item = Item::factory()->create(['tenant_id' => $this->user->tenant_id]);
    useInServiceOrder($item);

    $this->delete(route('items.destroy', $item->id))->assertSessionHas('error');

    expect(Item::find($item->id))->not->toBeNull()
        ->and(StockMovement::where('item_id', $item->id)->count())->toBe(1);
});

test('an item can be deactivated and reactivated', function () {
    $item = Item::factory()->create(['tenant_id' => $this->user->tenant_id]);

    $this->put(route('items.toggle', $item->id))->assertSessionHas('success');
    expect($item->fresh()->active)->toBeFalse();

    $this->put(route('items.toggle', $item->id));
    expect($item->fresh()->active)->toBeTrue();
});

test('the list shows delete, deactivate or reactivate depending on the item', function () {
    $unused = Item::factory()->create(['tenant_id' => $this->user->tenant_id, 'name' => 'Nunca usado']);
    $used = Item::factory()->create(['tenant_id' => $this->user->tenant_id, 'name' => 'Já usado']);
    $inactive = Item::factory()->create(['tenant_id' => $this->user->tenant_id, 'name' => 'Desativado', 'active' => false]);
    useInServiceOrder($used);

    $this->get(route('items.index'))
        ->assertOk()
        ->assertSee(route('items.destroy', $unused->id))
        ->assertDontSee(route('items.destroy', $used->id))
        ->assertSee(route('items.toggle', $used->id))
        ->assertSee(route('items.toggle', $inactive->id))
        ->assertSee('Inativo')
        ->assertSee('Reativar');
});

test('an inactive item is not offered nor accepted in service orders', function () {
    $item = Item::factory()->create(['tenant_id' => $this->user->tenant_id, 'name' => 'Peça antiga', 'active' => false]);
    $serviceOrder = ServiceOrder::factory()->create(['tenant_id' => $this->user->tenant_id]);

    Livewire::test(ItemForm::class, ['serviceOrder' => $serviceOrder])
        ->assertDontSee('Peça antiga')
        ->set('item_id', $item->id)
        ->call('save')
        ->assertHasErrors('item_id');

    expect($serviceOrder->items()->count())->toBe(0);
});

test('an inactive item is not listed as low stock', function () {
    Item::factory()->create(['tenant_id' => $this->user->tenant_id, 'name' => 'Peça antiga', 'stock' => 0, 'active' => false]);

    $this->get(route('dashboard'))->assertDontSee('Peça antiga');
});

// Isolamento entre oficinas

test('items from another tenant are not listed', function () {
    $other = User::factory()->create();
    Item::factory()->create(['tenant_id' => $other->tenant_id, 'name' => 'Item de outra oficina']);

    $this->get(route('items.index'))->assertDontSee('Item de outra oficina');
});

test('items from another tenant cannot be edited, deleted or toggled', function () {
    $other = User::factory()->create();
    $item = Item::factory()->create(['tenant_id' => $other->tenant_id, 'name' => 'Item de outra oficina']);

    $this->get(route('items.edit', $item->id))->assertNotFound();
    $this->put(route('items.update', $item->id), itemData())->assertNotFound();
    $this->delete(route('items.destroy', $item->id))->assertNotFound();
    $this->put(route('items.toggle', $item->id))->assertNotFound();

    expect($item->fresh()->name)->toBe('Item de outra oficina')
        ->and($item->fresh()->active)->toBeTrue();
});
