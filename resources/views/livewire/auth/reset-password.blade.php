<?php

use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Locked;
use Livewire\Volt\Component;

new #[Layout('components.layouts.auth'), Title('Redefinir senha')] class extends Component {
    #[Locked]
    public string $token = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Mount the component.
     */
    public function mount(string $token): void
    {
        $this->token = $token;

        $this->email = request()->string('email');
    }

    /**
     * Reset the password for the given user.
     */
    public function resetPassword(): void
    {
        $this->validate([
            'token' => ['required'],
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        // Here we will attempt to reset the user's password. If it is successful we
        // will update the password on an actual user model and persist it to the
        // database. Otherwise we will parse the error and return the response.
        $status = Password::reset(
            $this->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) {
                $user->forceFill([
                    'password' => Hash::make($this->password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        // If the password was successfully reset, we will redirect the user back to
        // the application's home authenticated view. If there is an error we can
        // redirect them back to where they came from with their error message.
        if ($status != Password::PasswordReset) {
            $this->addError('email', __($status));

            return;
        }

        Session::flash('status', __($status));

        $this->redirectRoute('login', navigate: true);
    }
}; ?>

<div>
    <x-auth-header badge="Nova senha" title="Redefinir senha"
        description="Crie uma nova senha com pelo menos 8 caracteres." />

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form wire:submit="resetPassword">

        <div class="mb-3">
            <label for="email" class="form-label fw-semibold">E-mail</label>

            <div class="input-group has-validation">
                <span class="input-group-text bg-light border-end-0">
                    <i class="bi bi-envelope text-secondary"></i>
                </span>

                <input type="email" id="email" wire:model="email" required autocomplete="email"
                    class="form-control bg-light border-start-0 @error('email') is-invalid @enderror">

                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>


        <div class="row">

            <div class="col-md-6 mb-3">
                <label for="password" class="form-label fw-semibold">Nova senha</label>

                <div class="input-group has-validation">
                    <span class="input-group-text bg-light border-end-0">
                        <i class="bi bi-lock text-secondary"></i>
                    </span>

                    <input type="password" id="password" wire:model="password" required autofocus
                        autocomplete="new-password"
                        class="form-control bg-light border-start-0 @error('password') is-invalid @enderror">

                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>


            <div class="col-md-6 mb-3">
                <label for="password_confirmation" class="form-label fw-semibold">Confirme a senha</label>

                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0">
                        <i class="bi bi-lock text-secondary"></i>
                    </span>

                    <input type="password" id="password_confirmation" wire:model="password_confirmation" required
                        autocomplete="new-password" class="form-control bg-light border-start-0">
                </div>
            </div>

        </div>


        <button type="submit" class="btn btn-brand w-100 py-2 fw-semibold mt-2">
            <i class="bi bi-check-lg me-2"></i>
            Redefinir senha
        </button>

    </form>
</div>
