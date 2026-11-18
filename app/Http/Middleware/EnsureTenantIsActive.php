<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureTenantIsActive
{
    /**
     * Quem já estava logado quando a oficina foi suspensa é desconectado na próxima ação.
     * (O login em si também recusa oficinas suspensas.)
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user()?->tenant?->isSuspended()) {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->with('suspended', __('auth.suspended'));
        }

        return $next($request);
    }
}
