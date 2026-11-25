<?php

use App\Models\ServiceOrder;
use App\Models\Tenant;
use App\Models\User;

beforeEach(function () {
    $this->owner = User::factory()->create();
    $this->owner->forceFill(['role' => 'admin'])->save();
    $this->tenant = $this->owner->tenant;

    $this->actingAs($this->owner);
});

function workshopData(array $overrides = []): array
{
    return array_merge([
        'name' => 'Auto Center Silva',
        'document' => '11.222.333/0001-81',
        'email' => 'contato@silva.test',
        'phone' => '(11) 98765-4321',
        'zip_code' => '01001-000',
        'street' => 'Praça da Sé',
        'number' => '100',
        'complement' => 'Galpão 2',
        'district' => 'Sé',
        'city' => 'São Paulo',
        'state' => 'sp',
    ], $overrides);
}

test('the owner sees the workshop page and the menu link', function () {
    $this->get(route('workshop.edit'))
        ->assertOk()
        ->assertSee('Dados da oficina')
        ->assertSee(route('workshop.update'));

    $this->get(route('dashboard'))->assertSee(route('workshop.edit'));
});

test('the owner can update the workshop data, stored without punctuation', function () {
    $domain = $this->tenant->domain;

    $this->put(route('workshop.update'), workshopData())
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('workshop.edit'));

    $tenant = $this->tenant->fresh();

    expect($tenant->name)->toBe('Auto Center Silva')
        ->and($tenant->document)->toBe('11222333000181')
        ->and($tenant->zip_code)->toBe('01001000')
        ->and($tenant->state)->toBe('SP')
        ->and($tenant->city)->toBe('São Paulo')
        // O identificador interno não acompanha o nome
        ->and($tenant->domain)->toBe($domain);
});

test('the form shows the document and zip code formatted', function () {
    $this->tenant->update(['document' => '52998224725', 'zip_code' => '01001000']);

    $this->get(route('workshop.edit'))
        ->assertSee('value="529.982.247-25"', false)
        ->assertSee('value="01001-000"', false);
});

test('a valid CPF is accepted', function () {
    $this->put(route('workshop.update'), workshopData(['document' => '529.982.247-25']))
        ->assertSessionHasNoErrors();

    expect($this->tenant->fresh()->documentLabel())->toBe('CPF 529.982.247-25');
});

test('invalid CPF or CNPJ numbers are rejected', function (string $document) {
    $this->put(route('workshop.update'), workshopData(['document' => $document]))
        ->assertSessionHasErrors('document');
})->with([
    'wrong CNPJ check digit' => '11.222.333/0001-82',
    'wrong CPF check digit' => '529.982.247-26',
    'repeated digits' => '111.111.111-11',
    'wrong length' => '1234567',
]);

test('the document is optional', function () {
    $this->put(route('workshop.update'), workshopData(['document' => '']))
        ->assertSessionHasNoErrors();

    expect($this->tenant->fresh()->document)->toBeNull();
});

test('a document already used by another workshop is rejected, even typed differently', function () {
    Tenant::factory()->create(['document' => '11222333000181']);

    $this->put(route('workshop.update'), workshopData(['document' => '11.222.333/0001-81']))
        ->assertSessionHasErrors(['document' => 'Este CPF/CNPJ já está cadastrado em outra oficina.']);
});

test('saving again with its own document is fine', function () {
    $this->tenant->update(['document' => '11222333000181']);

    $this->put(route('workshop.update'), workshopData())->assertSessionHasNoErrors();
});

test('the state must be a brazilian UF and the zip code must have 8 digits', function () {
    $this->put(route('workshop.update'), workshopData(['state' => 'XX', 'zip_code' => '0100']))
        ->assertSessionHasErrors(['state', 'zip_code']);
});

test('the request can not change the internal identifier or the status', function () {
    $domain = $this->tenant->domain;

    $this->put(route('workshop.update'), workshopData(['domain' => 'hacked', 'status' => 'suspended']));

    expect($this->tenant->fresh()->domain)->toBe($domain)
        ->and($this->tenant->fresh()->status)->toBe('active');
});

test('a user who is not the owner gets not found and sees no menu link', function () {
    $employee = User::factory()->employee()->create(['tenant_id' => $this->tenant->id]);

    $this->actingAs($employee);

    $this->get(route('workshop.edit'))->assertNotFound();
    $this->put(route('workshop.update'), workshopData(['name' => 'Invadida']))->assertNotFound();
    $this->get(route('dashboard'))->assertDontSee(route('workshop.edit'));

    expect($this->tenant->fresh()->name)->not->toBe('Invadida');
});

test('the printed service order shows the workshop address and document', function () {
    $this->tenant->update([
        'document' => '11222333000181',
        'zip_code' => '01001000',
        'street' => 'Praça da Sé',
        'number' => '100',
        'district' => 'Sé',
        'city' => 'São Paulo',
        'state' => 'SP',
    ]);
    $order = ServiceOrder::factory()->create(['tenant_id' => $this->tenant->id]);

    $this->get(route('service-orders.print', $order->id))
        ->assertOk()
        ->assertSee('Praça da Sé, 100 · Sé · São Paulo/SP · CEP 01001-000')
        ->assertSee('CNPJ 11.222.333/0001-81');
});
