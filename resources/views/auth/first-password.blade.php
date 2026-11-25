<x-layouts.auth title="Defina sua senha">

    <x-auth-header badge="Primeiro acesso" title="Defina sua senha"
        description="Você entrou com uma senha provisória. Escolha uma senha só sua para continuar." />

    <form action="{{ route('password.first.update') }}" method="POST">

        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="password" class="form-label fw-semibold">Nova senha</label>

            <div class="input-group has-validation">
                <span class="input-group-text bg-light border-end-0">
                    <i class="bi bi-lock text-secondary"></i>
                </span>

                <input type="password" id="password" name="password" required autofocus autocomplete="new-password"
                    class="form-control bg-light border-start-0 @error('password') is-invalid @enderror">

                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-text">Pelo menos 8 caracteres.</div>
        </div>


        <div class="mb-4">
            <label for="password_confirmation" class="form-label fw-semibold">Confirme a nova senha</label>

            <div class="input-group">
                <span class="input-group-text bg-light border-end-0">
                    <i class="bi bi-lock-fill text-secondary"></i>
                </span>

                <input type="password" id="password_confirmation" name="password_confirmation" required
                    autocomplete="new-password" class="form-control bg-light border-start-0">
            </div>
        </div>


        <button type="submit" class="btn btn-brand w-100 py-2 fw-semibold">
            <i class="bi bi-check-lg me-2"></i>
            Salvar e entrar
        </button>

    </form>


    <form action="{{ route('logout') }}" method="POST" class="text-center mt-3">
        @csrf
        <button type="submit" class="btn btn-link small text-secondary text-decoration-none">
            Sair
        </button>
    </form>

</x-layouts.auth>
