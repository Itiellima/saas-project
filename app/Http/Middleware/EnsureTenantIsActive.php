<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureTenantIsActive
{
    /**
     * Quem já estava logado é desconectado na próxima ação quando a oficina é suspensa
     * ou quando o próprio usuário é desativado pelo dono. (O login também recusa os dois.)
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        $message = match (true) {
            (bool) $user?->tenant?->isSuspended() => __('auth.suspended'),
            $user !== null && ! $user->isActive() => __('auth.deactivated'),
            default => null,
        };

        if ($message) {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->with('suspended', $message);
        }

        return $next($request);
    }
}
