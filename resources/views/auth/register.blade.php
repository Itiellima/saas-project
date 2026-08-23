@extends('layouts.auth')

@section('title', 'Register')

@section('content')

<div class="min-vh-100 d-flex align-items-center py-5" style="background-color: #f8f9fa;">

    <div class="container">

        <div class="row justify-content-center">

            <div class="col-12 col-lg-10 col-xl-9">

                <div class="card border-0 shadow-lg overflow-hidden rounded-4">

                    <div class="row g-0">

                        {{-- LADO ESQUERDO --}}
                        <div
                            class="col-lg-5 d-none d-lg-flex flex-column justify-content-between p-5 text-white"
                            style="background: linear-gradient(135deg, #ff7a00, #ff4d00);"
                        >

                            <div>

                                <div class="mb-5">
                                    <h2 class="fw-bold mb-1">
                                        <i class="fa-solid fa-wrench me-2"></i>
                                        OficinaOS
                                    </h2>

                                    <small class="opacity-75">
                                        Gestão simples para o seu negócio
                                    </small>
                                </div>

                                <h3 class="fw-bold mb-3">
                                    Comece a gerenciar
                                    sua empresa.
                                </h3>

                                <p class="opacity-75">
                                    Organize clientes, produtos, serviços e
                                    ordens de serviço em um único lugar.
                                </p>

                            </div>

                            <div>

                                <div class="d-flex align-items-center mb-3">
                                    <i class="fa-solid fa-circle-check me-3 fs-5"></i>
                                    <span>Gestão de clientes</span>
                                </div>

                                <div class="d-flex align-items-center mb-3">
                                    <i class="fa-solid fa-circle-check me-3 fs-5"></i>
                                    <span>Produtos e serviços</span>
                                </div>

                                <div class="d-flex align-items-center">
                                    <i class="fa-solid fa-circle-check me-3 fs-5"></i>
                                    <span>Ordens de serviço</span>
                                </div>

                            </div>

                        </div>


                        {{-- FORMULÁRIO --}}
                        <div class="col-lg-7 bg-white">

                            <div class="p-4 p-md-5">

                                <div class="mb-4">

                                    <span class="badge rounded-pill px-3 py-2 mb-3"
                                        style="background-color: #fff1e6; color: #ff6500;">
                                        Novo cadastro
                                    </span>

                                    <h3 class="fw-bold mb-2">
                                        Crie sua conta
                                    </h3>

                                    <p class="text-secondary mb-0">
                                        Cadastre sua empresa para começar.
                                    </p>

                                </div>


                                <form action="{{ route('register.store') }}" method="POST">

                                    @csrf

                                    <div class="mb-3">
                                        <label for="tenant_name" class="form-label fw-semibold">
                                            Nome da empresa
                                        </label>

                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-end-0">
                                                <i class="fa-solid fa-building text-secondary"></i>
                                            </span>

                                            <input
                                                type="text"
                                                class="form-control bg-light border-start-0"
                                                id="tenant_name"
                                                name="tenant_name"
                                                placeholder="Ex.: Oficina do João"
                                                value="{{ old('tenant_name') }}"
                                                required
                                                autofocus
                                            >
                                        </div>

                                        @error('tenant_name')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>


                                    <div class="mb-3">
                                        <label for="user_name" class="form-label fw-semibold">
                                            Seu nome
                                        </label>

                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-end-0">
                                                <i class="fa-solid fa-user text-secondary"></i>
                                            </span>

                                            <input
                                                type="text"
                                                class="form-control bg-light border-start-0"
                                                id="user_name"
                                                name="user_name"
                                                placeholder="Seu nome"
                                                value="{{ old('user_name') }}"
                                                required
                                            >
                                        </div>

                                        @error('user_name')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>


                                    <div class="mb-3">
                                        <label for="user_email" class="form-label fw-semibold">
                                            E-mail
                                        </label>

                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-end-0">
                                                <i class="fa-solid fa-envelope text-secondary"></i>
                                            </span>

                                            <input
                                                type="email"
                                                class="form-control bg-light border-start-0"
                                                id="user_email"
                                                name="user_email"
                                                placeholder="seu@email.com"
                                                value="{{ old('user_email') }}"
                                                required
                                            >
                                        </div>

                                        @error('user_email')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>


                                    <div class="row">

                                        <div class="col-md-6 mb-3">

                                            <label for="password" class="form-label fw-semibold">
                                                Senha
                                            </label>

                                            <div class="input-group">
                                                <span class="input-group-text bg-light border-end-0">
                                                    <i class="fa-solid fa-lock text-secondary"></i>
                                                </span>

                                                <input
                                                    type="password"
                                                    class="form-control bg-light border-start-0"
                                                    id="password"
                                                    name="password"
                                                    required
                                                >
                                            </div>

                                            @error('password')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror

                                        </div>


                                        <div class="col-md-6 mb-3">

                                            <label for="password_confirmation" class="form-label fw-semibold">
                                                Confirmar senha
                                            </label>

                                            <div class="input-group">
                                                <span class="input-group-text bg-light border-end-0">
                                                    <i class="fa-solid fa-lock text-secondary"></i>
                                                </span>

                                                <input
                                                    type="password"
                                                    class="form-control bg-light border-start-0"
                                                    id="password_confirmation"
                                                    name="password_confirmation"
                                                    required
                                                >
                                            </div>

                                        </div>

                                    </div>


                                    <button
                                        type="submit"
                                        class="btn text-white w-100 py-2 fw-semibold mt-2"
                                        style="background-color: #ff6500;"
                                    >
                                        <i class="fa-solid fa-rocket me-2"></i>
                                        Criar minha conta
                                    </button>

                                </form>


                                <div class="text-center mt-4">

                                    <small class="text-secondary">
                                        Já possui uma conta?
                                    </small>

                                    <a
                                        href="{{ route('login') }}"
                                        class="text-decoration-none fw-semibold ms-1"
                                        style="color: #ff6500;"
                                    >
                                        Entrar
                                    </a>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>