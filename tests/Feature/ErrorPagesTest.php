<?php

use App\Models\User;
use Symfony\Component\HttpKernel\Exception\HttpException;

test('a missing page shows the portuguese 404 page', function () {
    $this->get('/pagina-que-nao-existe')
        ->assertNotFound()
        ->assertSee('Página não encontrada')
        ->assertSee('Ir para o painel')
        ->assertSee('favicon.svg');
});

test('a service order from another workshop shows the 404 page', function () {
    $this->actingAs(User::factory()->create());
    $other = App\Models\ServiceOrder::factory()->create();

    $this->get(route('service-orders.show', $other->id))
        ->assertNotFound()
        ->assertSee('Página não encontrada');
});

test('each error page renders in portuguese', function (int $code, string $title) {
    $html = view("errors.{$code}", ['exception' => new HttpException($code)])->render();

    expect($html)->toContain($title)
        ->toContain("Erro {$code}")
        ->toContain('OficinaOS');
})->with([
    [403, 'Acesso não permitido'],
    [404, 'Página não encontrada'],
    [419, 'Sua sessão expirou'],
    [429, 'Muitas tentativas'],
    [500, 'Algo deu errado'],
    [503, 'Estamos em manutenção'],
]);

test('the 403 page shows custom messages but not the default english one', function () {
    $custom = view('errors.403', ['exception' => new HttpException(403, 'Esta OS está finalizada.')])->render();
    $default = view('errors.403', ['exception' => new HttpException(403, 'This action is unauthorized.')])->render();

    expect($custom)->toContain('Esta OS está finalizada.')
        ->and($default)->toContain('Você não tem permissão para fazer isso.')
        ->not->toContain('This action is unauthorized.');
});

test('registration is limited to 5 attempts per minute', function () {
    $data = fn (int $i) => [
        'tenant_name' => "Oficina {$i}",
        'user_name' => 'Teste',
        'user_email' => "teste{$i}@example.com",
        'password' => 'senha-segura-123',
        'password_confirmation' => 'senha-segura-123',
    ];

    foreach (range(1, 5) as $i) {
        $this->post('/register', $data($i));
        auth()->logout();
    }

    $this->post('/register', $data(6))
        ->assertTooManyRequests()
        ->assertSee('Muitas tentativas');

    expect(App\Models\Tenant::count())->toBe(5);
});
