<?php

use App\Models\User;

test('the guest screens use the OficinaOS layout', function (string $url, string $title, string $heading) {
    $this->get($url)
        ->assertOk()
        ->assertSee("<title>{$title} - OficinaOS</title>", false)
        ->assertSee($heading)
        ->assertSee('Sua oficina organizada em um só lugar.')
        ->assertDontSee('flux', false)
        ->assertDontSee('fa-solid', false);
})->with([
    'login' => ['/login', 'Entrar', 'Entre na sua conta'],
    'cadastro' => ['/register', 'Criar conta', 'Crie sua conta'],
    'esqueci a senha' => ['/forgot-password', 'Esqueceu a senha', 'Esqueceu a senha?'],
    'redefinir senha' => ['/reset-password/token-qualquer', 'Redefinir senha', 'Redefinir senha'],
]);

test('the logged in auth screens use the OficinaOS layout', function (string $url, string $title) {
    $this->actingAs(User::factory()->unverified()->create())
        ->get($url)
        ->assertOk()
        ->assertSee("<title>{$title} - OficinaOS</title>", false)
        ->assertDontSee('flux', false);
})->with([
    'confirmar senha' => ['/confirm-password', 'Confirmar senha'],
    'confirmar e-mail' => ['/verify-email', 'Confirmar e-mail'],
]);

test('the login screen links to password recovery and sign up', function () {
    $this->get('/login')
        ->assertSee(route('password.request'))
        ->assertSee(route('register'));
});
