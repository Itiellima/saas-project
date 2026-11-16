<?php

// No Easypanel o HTTPS termina no proxy (Traefik), que repassa a requisição como http
test('urls use https when the proxy says the request was https', function () {
    $this->get('/', ['X-Forwarded-Proto' => 'https', 'X-Forwarded-Host' => 'oficina.example.com'])
        ->assertOk()
        ->assertSee('https://oficina.example.com/register');
});
