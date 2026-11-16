<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Volt\Component;

new #[Layout('components.layouts.auth'), Title('Confirmar senha')] class extends Component {
    public string $password = '';

    /**
     * Confirm the current user's password.
     */
    public function confirmPassword(): void
    {
        $this->validate([
            'password' => ['required', 'string'],
        ]);

        if (! Auth::guard('web')->validate([
            'email' => Auth::user()->email,
            'password' => $this->password,
        ])) {
            throw ValidationException::withMessages([
                'password' => __('auth.password'),
            ]);
        }

        session(['auth.password_confirmed_at' => time()]);

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div>
    <x-auth-header badge="Área protegida" title="Confirme sua senha"
        description="Por segurança, confirme sua senha para continuar." />

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form wire:submit="confirmPassword">

        <div class="mb-4">
            <label for="password" class="form-label fw-semibold">Senha</label>

            <div class="input-group has-validation">
                <span class="input-group-text bg-light border-end-0">
                    <i class="bi bi-lock text-secondary"></i>
                </span>

                <input type="password" id="password" wire:model="password" required autofocus
                    autocomplete="current-password"
                    class="form-control bg-light border-start-0 @error('password') is-invalid @enderror">

                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>


        <button type="submit" class="btn btn-brand w-100 py-2 fw-semibold">
            <i class="bi bi-shield-check me-2"></i>
            Confirmar
        </button>

    </form>
</div>
