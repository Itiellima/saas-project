<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Em produção o app fica atrás do proxy do Easypanel (Traefik), que cuida do HTTPS.
        // Sem isto, o Laravel acha que a requisição é http:// e gera links e redirecionamentos errados.
        $middleware->trustProxies(at: '*');

        // Oficina suspensa: desconecta quem ainda estava logado
        $middleware->web(append: [
            \App\Http\Middleware\EnsureTenantIsActive::class,
            // Funcionário com senha provisória: só acessa a tela de troca de senha
            \App\Http\Middleware\EnsurePasswordChanged::class,
        ]);

        $middleware->alias([
            'super-admin' => \App\Http\Middleware\EnsureSuperAdmin::class,
            'owner' => \App\Http\Middleware\EnsureWorkshopOwner::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
