<x-layouts.auth title="Criar conta">

    <x-auth-header badge="Novo cadastro" title="Crie sua conta"
        description="Cadastre sua oficina para começar. Leva menos de um minuto." />

    @error('error')
        <div class="alert alert-danger d-flex align-items-center gap-2" role="alert">
            <i class="bi bi-exclamation-triangle-fill"></i>
            <span>{{ $message }}</span>
        </div>
    @enderror

    <form action="{{ route('register.store') }}" method="POST">

        @csrf

        <div class="mb-3">
            <label for="tenant_name" class="form-label fw-semibold">Nome da oficina</label>

            <div class="input-group has-validation">
                <span class="input-group-text bg-light border-end-0">
                    <i class="bi bi-building text-secondary"></i>
                </span>

                <input type="text" id="tenant_name" name="tenant_name" value="{{ old('tenant_name') }}" required
                    autofocus placeholder="Ex.: Oficina do João"
                    class="form-control bg-light border-start-0 @error('tenant_name') is-invalid @enderror">

                @error('tenant_name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>


        <div class="mb-3">
            <label for="user_name" class="form-label fw-semibold">Seu nome</label>

            <div class="input-group has-validation">
                <span class="input-group-text bg-light border-end-0">
                    <i class="bi bi-person text-secondary"></i>
                </span>

                <input type="text" id="user_name" name="user_name" value="{{ old('user_name') }}" required
                    autocomplete="name" placeholder="Seu nome"
                    class="form-control bg-light border-start-0 @error('user_name') is-invalid @enderror">

                @error('user_name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>


        <div class="mb-3">
            <label for="user_email" class="form-label fw-semibold">E-mail</label>

            <div class="input-group has-validation">
                <span class="input-group-text bg-light border-end-0">
                    <i class="bi bi-envelope text-secondary"></i>
                </span>

                <input type="email" id="user_email" name="user_email" value="{{ old('user_email') }}" required
                    autocomplete="email" placeholder="seu@email.com"
                    class="form-control bg-light border-start-0 @error('user_email') is-invalid @enderror">

                @error('user_email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>


        <div class="row">

            <div class="col-md-6 mb-3">
                <label for="password" class="form-label fw-semibold">Senha</label>

                <div class="input-group has-validation">
                    <span class="input-group-text bg-light border-end-0">
                        <i class="bi bi-lock text-secondary"></i>
                    </span>

                    <input type="password" id="password" name="password" required autocomplete="new-password"
                        class="form-control bg-light border-start-0 @error('password') is-invalid @enderror">

                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <small class="text-secondary">Pelo menos 8 caracteres.</small>
            </div>


            <div class="col-md-6 mb-3">
                <label for="password_confirmation" class="form-label fw-semibold">Confirme a senha</label>

                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0">
                        <i class="bi bi-lock text-secondary"></i>
                    </span>

                    <input type="password" id="password_confirmation" name="password_confirmation" required
                        autocomplete="new-password" class="form-control bg-light border-start-0">
                </div>
            </div>

        </div>


        <button type="submit" class="btn btn-brand w-100 py-2 fw-semibold mt-2">
            <i class="bi bi-rocket-takeoff me-2"></i>
            Criar minha conta
        </button>

    </form>


    <div class="text-center mt-4">
        <small class="text-secondary">Já possui uma conta?</small>
        <a href="{{ route('login') }}" class="text-decoration-none fw-semibold ms-1 link-brand">Entrar</a>
    </div>

</x-layouts.auth>
