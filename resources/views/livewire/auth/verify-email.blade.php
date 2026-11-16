<?php

use App\Livewire\Actions\Logout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Volt\Component;

new #[Layout('components.layouts.auth'), Title('Confirmar e-mail')] class extends Component {
    /**
     * Send an email verification notification to the user.
     */
    public function sendVerification(): void
    {
        if (Auth::user()->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);

            return;
        }

        Auth::user()->sendEmailVerificationNotification();

        Session::flash('status', 'verification-link-sent');
    }

    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }
}; ?>

<div>
    <x-auth-header badge="Quase lá" title="Confirme seu e-mail"
        description="Enviamos um link de confirmação para o seu e-mail. Clique nele para ativar sua conta." />

    @if (session('status') == 'verification-link-sent')
        <x-auth-session-status class="mb-4"
            status="Um novo link de confirmação foi enviado para o e-mail informado no cadastro." />
    @endif

    <button type="button" wire:click="sendVerification" class="btn btn-brand w-100 py-2 fw-semibold">
        <i class="bi bi-envelope-arrow-up me-2"></i>
        Reenviar e-mail de confirmação
    </button>

    <div class="text-center mt-4">
        <button type="button" wire:click="logout" class="btn btn-link text-secondary text-decoration-none">
            <i class="bi bi-box-arrow-right me-1"></i>
            Sair
        </button>
    </div>
</div>
