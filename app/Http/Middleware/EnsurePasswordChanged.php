<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsurePasswordChanged
{
    /**
     * Rotas liberadas enquanto a senha provisória não é trocada.
     */
    private const ALLOWED_ROUTES = ['password.first', 'password.first.update', 'logout'];

    /**
     * Com senha provisória, o funcionário só acessa a tela de troca de senha (e o "Sair").
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user()?->must_change_password && ! $request->routeIs(...self::ALLOWED_ROUTES)) {
            return redirect()->route('password.first');
        }

        return $next($request);
    }
}
