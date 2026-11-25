<?php

use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Volt\Component;

new #[Layout('components.layouts.auth'), Title('Entrar')] class extends Component {
    #[Validate('required|string|email')]
    public string $email = '';

    #[Validate('required|string')]
    public string $password = '';

    public bool $remember = false;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->ensureIsNotRateLimited();

        if (! Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        RateLimiter::clear($this->throttleKey());

        // Senha certa, mas a oficina está suspensa ou o usuário foi desativado: não deixa entrar
        $blocked = match (true) {
            (bool) Auth::user()->tenant?->isSuspended() => __('auth.suspended'),
            ! Auth::user()->isActive() => __('auth.deactivated'),
            default => null,
        };

        if ($blocked) {
            Auth::logout();

            throw ValidationException::withMessages([
                'email' => $blocked,
            ]);
        }

        Session::regenerate();

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }

    /**
     * Ensure the authentication request is not rate limited.
     */
    protected function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout(request()));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => __('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the authentication rate limiting throttle key.
     */
    protected function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->email).'|'.request()->ip());
    }
}; ?>

<div>
    <x-auth-header badge="Bem-vindo de volta" title="Entre na sua conta"
        description="Informe seu e-mail e senha para acessar o sistema." />

    <x-auth-session-status class="mb-4" :status="session('status')" />

    @if (session('suspended'))
        <div class="alert alert-danger d-flex align-items-center gap-2 mb-4" role="alert">
            <i class="bi bi-slash-circle-fill"></i>
            <span>{{ session('suspended') }}</span>
        </div>
    @endif

    <form wire:submit="login">

        <div class="mb-3">
            <label for="email" class="form-label fw-semibold">E-mail</label>

            <div class="input-group has-validation">
                <span class="input-group-text bg-light border-end-0">
                    <i class="bi bi-envelope text-secondary"></i>
                </span>

                <input type="email" id="email" wire:model="email" required autofocus autocomplete="email"
                    placeholder="seu@email.com"
                    class="form-control bg-light border-start-0 @error('email') is-invalid @enderror">

                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>


        <div class="mb-3">
            <div class="d-flex justify-content-between">
                <label for="password" class="form-label fw-semibold">Senha</label>

                <a href="{{ route('password.request') }}" class="small text-decoration-none link-brand" wire:navigate>
                    Esqueceu a senha?
                </a>
            </div>

            <div class="input-group has-validation">
                <span class="input-group-text bg-light border-end-0">
                    <i class="bi bi-lock text-secondary"></i>
                </span>

                <input type="password" id="password" wire:model="password" required autocomplete="current-password"
                    class="form-control bg-light border-start-0 @error('password') is-invalid @enderror">

                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>


        <div class="form-check mb-4">
            <input type="checkbox" id="remember" wire:model="remember" class="form-check-input">
            <label for="remember" class="form-check-label">Lembrar de mim</label>
        </div>


        <button type="submit" class="btn btn-brand w-100 py-2 fw-semibold">
            <span wire:loading.remove wire:target="login">
                <i class="bi bi-box-arrow-in-right me-2"></i>
                Entrar
            </span>
            <span wire:loading wire:target="login">
                <span class="spinner-border spinner-border-sm me-2"></span>
                Entrando...
            </span>
        </button>

    </form>


    <div class="text-center mt-4">
        <small class="text-secondary">Ainda não tem conta?</small>
        <a href="{{ route('register') }}" class="text-decoration-none fw-semibold ms-1 link-brand">Cadastre-se</a>
    </div>
</div>
