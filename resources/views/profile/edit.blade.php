@extends('layouts.main')

@section('title', 'Perfil - OficinaOS')
@section('page_title', 'Perfil')

@section('content')

    <div class="container-fluid px-3 px-md-4 py-4">

        {{-- CABEÇALHO --}}
        <div class="mb-4">

            <div class="d-flex align-items-center gap-2 mb-2">

                <div class="rounded-3 d-flex align-items-center justify-content-center"
                    style="width: 45px; height: 45px; background-color: #fff1e6;">
                    <i class="bi bi-person" style="color: #ff6500;"></i>
                </div>

                <div>
                    <h3 class="fw-bold mb-0">
                        Meu perfil
                    </h3>

                    <small class="text-secondary">
                        Atualize seus dados de acesso e sua senha.
                    </small>
                </div>

            </div>

        </div>


        <div class="row justify-content-center">

            <div class="col-12 col-xl-9">

                {{-- DADOS PESSOAIS --}}
                <form action="{{ route('profile.update') }}" method="POST" class="card border-0 shadow-sm mb-4">

                    @csrf
                    @method('PUT')

                    <div class="card-header bg-white border-0 pt-4 px-4">

                        <h5 class="fw-bold mb-1">
                            <i class="bi bi-person-vcard me-2" style="color: #ff6500;"></i>
                            Dados pessoais
                        </h5>

                        <small class="text-secondary">
                            O e-mail é usado para entrar no sistema.
                        </small>

                    </div>


                    <div class="card-body p-4">

                        <div class="mb-4">

                            <label for="name" class="form-label fw-semibold">
                                Nome
                            </label>

                            <input type="text" id="name" name="name" required
                                class="form-control @error('name') is-invalid @enderror"
                                value="{{ old('name', $user->name) }}">

                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                        </div>


                        <div class="mb-4">

                            <label for="email" class="form-label fw-semibold">
                                E-mail
                            </label>

                            <input type="email" id="email" name="email" required
                                class="form-control @error('email') is-invalid @enderror"
                                value="{{ old('email', $user->email) }}">

                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                        </div>


                        <div class="d-flex justify-content-end">

                            <button type="submit" class="btn text-white fw-semibold px-4"
                                style="background-color: #ff6500;">
                                <i class="bi bi-check me-2"></i>
                                Salvar alterações
                            </button>

                        </div>

                    </div>

                </form>


                {{-- ALTERAR SENHA --}}
                <form action="{{ route('profile.password') }}" method="POST" class="card border-0 shadow-sm mb-4">

                    @csrf
                    @method('PUT')

                    <div class="card-header bg-white border-0 pt-4 px-4">

                        <h5 class="fw-bold mb-1">
                            <i class="bi bi-lock me-2" style="color: #ff6500;"></i>
                            Alterar senha
                        </h5>

                        <small class="text-secondary">
                            Use pelo menos 8 caracteres.
                        </small>

                    </div>


                    <div class="card-body p-4">

                        <div class="mb-4">

                            <label for="current_password" class="form-label fw-semibold">
                                Senha atual
                            </label>

                            <input type="password" id="current_password" name="current_password" required
                                autocomplete="current-password"
                                class="form-control @error('current_password') is-invalid @enderror">

                            @error('current_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                        </div>


                        <div class="row">

                            <div class="col-md-6 mb-4">

                                <label for="new_password" class="form-label fw-semibold">
                                    Nova senha
                                </label>

                                <input type="password" id="new_password" name="password" required
                                    autocomplete="new-password"
                                    class="form-control @error('password') is-invalid @enderror">

                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                            </div>


                            <div class="col-md-6 mb-4">

                                <label for="new_password_confirmation" class="form-label fw-semibold">
                                    Confirme a nova senha
                                </label>

                                <input type="password" id="new_password_confirmation" name="password_confirmation"
                                    required autocomplete="new-password" class="form-control">

                            </div>

                        </div>


                        <div class="d-flex justify-content-end">

                            <button type="submit" class="btn text-white fw-semibold px-4"
                                style="background-color: #ff6500;">
                                <i class="bi bi-key me-2"></i>
                                Alterar senha
                            </button>

                        </div>

                    </div>

                </form>

            </div>

        </div>

    </div>

@endsection
