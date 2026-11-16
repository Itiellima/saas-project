<?php

use Illuminate\Support\Facades\Password;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Volt\Component;

new #[Layout('components.layouts.auth'), Title('Esqueceu a senha')] class extends Component {
    public string $email = '';

    /**
     * Send a password reset link to the provided email address.
     */
    public function sendPasswordResetLink(): void
    {
        $this->validate([
            'email' => ['required', 'string', 'email'],
        ]);

        Password::sendResetLink($this->only('email'));

        session()->flash('status', __('A reset link will be sent if the account exists.'));
    }
}; ?>

<div>
    <x-auth-header badge="Recuperar acesso" title="Esqueceu a senha?"
        description="Informe seu e-mail e enviaremos um link para você criar uma nova senha." />

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form wire:submit="sendPasswordResetLink">

        <div class="mb-4">
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


        <button type="submit" class="btn btn-brand w-100 py-2 fw-semibold">
            <span wire:loading.remove wire:target="sendPasswordResetLink">
                <i class="bi bi-send me-2"></i>
                Enviar link de redefinição
            </span>
            <span wire:loading wire:target="sendPasswordResetLink">
                <span class="spinner-border spinner-border-sm me-2"></span>
                Enviando...
            </span>
        </button>

    </form>


    <div class="text-center mt-4">
        <a href="{{ route('login') }}" class="text-decoration-none fw-semibold link-brand" wire:navigate>
            <i class="bi bi-arrow-left me-1"></i>
            Voltar para o login
        </a>
    </div>
</div>
