@extends('layouts.main')

@section('title', 'Nova Ordem de Serviço - OficinaOS')

@section('content')

    <div class="container-fluid px-3 px-md-4 py-4">

        {{-- CABEÇALHO --}}
        <div class="mb-4">

            <div class="d-flex align-items-center gap-3">

                <div class="rounded-3 d-flex align-items-center justify-content-center"
                    style="width: 50px; height: 50px; background-color: #fff1e6;">
                    <i class="fa-solid fa-file-circle-plus fs-5" style="color: #ff6500;"></i>
                </div>

                <div>

                    <h3 class="fw-bold mb-1">
                        Nova Ordem de Serviço
                    </h3>

                    <p class="text-secondary mb-0">
                        Crie uma nova OS e registre as informações do cliente e do veículo.
                    </p>

                </div>

            </div>

        </div>


        <form action="{{ route('service-orders.store') }}" method="POST">

            @csrf


            {{-- INFORMAÇÕES DO CLIENTE --}}
            <div class="card border-0 shadow-sm mb-4">

                <div class="card-header bg-white border-0 pt-4 px-4">

                    <h5 class="fw-bold mb-1">
                        <i class="fa-solid fa-user me-2" style="color: #ff6500;"></i>
                        Informações do cliente
                    </h5>

                    <small class="text-secondary">
                        Informe os dados do cliente responsável pelo veículo.
                    </small>

                </div>


                <div class="card-body p-4">

                    <div class="row g-3">

                        {{-- NOME --}}
                        <div class="col-12 col-md-8">

                            <label for="customer_name" class="form-label fw-semibold">
                                Nome do cliente
                            </label>

                            <div class="input-group">

                                <span class="input-group-text bg-light border-end-0">
                                    <i class="fa-solid fa-user text-secondary"></i>
                                </span>

                                <input type="text" id="customer_name" name="customer_name"
                                    class="form-control bg-light border-start-0" placeholder="Nome completo"
                                    oninput="this.value = this.value.toUpperCase();" value="{{ old('customer_name') }}"
                                    required autofocus>

                            </div>

                            @error('customer_name')
                                <div class="text-danger small mt-1">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- TELEFONE --}}
                        <div class="col-12 col-md-4">

                            <label for="customer_phone" class="form-label fw-semibold">
                                Telefone
                            </label>

                            <div class="input-group">

                                <span class="input-group-text bg-light border-end-0">
                                    <i class="fa-solid fa-phone text-secondary"></i>
                                </span>

                                <input type="text" id="customer_phone" name="customer_phone"
                                    class="form-control bg-light border-start-0" placeholder="(00) 00000-0000"
                                    maxlength="15" value="{{ old('customer_phone') }}">

                            </div>

                            @error('customer_phone')
                                <div class="text-danger small mt-1">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>

                    </div>

                </div>

            </div>


            {{-- INFORMAÇÕES DO VEÍCULO --}}
            <div class="card border-0 shadow-sm mb-4">

                <div class="card-header bg-white border-0 pt-4 px-4">

                    <h5 class="fw-bold mb-1">
                        <i class="fa-solid fa-car me-2" style="color: #ff6500;"></i>
                        Informações do veículo
                    </h5>

                    <small class="text-secondary">
                        Informe os dados do veículo que será atendido.
                    </small>

                </div>


                <div class="card-body p-4">

                    <div class="row g-3">

                        {{-- PLACA --}}
                        <div class="col-12 col-md-4">

                            <label for="vehicle_plate" class="form-label fw-semibold">
                                Placa
                            </label>

                            <input type="text" id="vehicle_plate" name="vehicle_plate" class="form-control"
                                placeholder="ABC1D23" maxlength="7" oninput="this.value = this.value.toUpperCase();"
                                value="{{ old('vehicle_plate') }}" required>

                            @error('vehicle_plate')
                                <div class="text-danger small mt-1">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- MODELO --}}
                        <div class="col-12 col-md-4">

                            <label for="vehicle_model" class="form-label fw-semibold">
                                Modelo
                            </label>

                            <input type="text" id="vehicle_model" name="vehicle_model" class="form-control"
                                placeholder="Ex.: Corolla" oninput="this.value = this.value.toUpperCase();"
                                value="{{ old('vehicle_model') }}">

                        </div>


                        {{-- KM --}}
                        <div class="col-12 col-md-4">

                            <label for="vehicle_km" class="form-label fw-semibold">
                                Quilometragem
                            </label>

                            <div class="input-group">

                                <input type="number" id="vehicle_km" name="vehicle_km" class="form-control" placeholder="0"
                                    min="0" value="{{ old('vehicle_km') }}">

                                <span class="input-group-text">
                                    km
                                </span>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- DATAS --}}
            <div class="card border-0 shadow-sm mb-4">

                <div class="card-header bg-white border-0 pt-4 px-4">

                    <h5 class="fw-bold mb-1">
                        <i class="fa-solid fa-calendar-days me-2" style="color: #ff6500;"></i>
                        Controle de entrada e saída
                    </h5>

                    <small class="text-secondary">
                        Registre quando o veículo entrou e quando foi entregue.
                    </small>

                </div>


                <div class="card-body p-4">

                    <div class="row g-3">

                        {{-- ENTRADA --}}
                        <div class="col-12 col-md-6">

                            <label for="vehicle_enter" class="form-label fw-semibold">
                                Entrada do veículo
                            </label>

                            <input type="datetime-local" id="vehicle_enter" name="vehicle_enter" class="form-control"
                                value="{{ old('vehicle_enter', now()->format('Y-m-d\TH:i')) }}">

                        </div>


                        {{-- SAÍDA --}}
                        <div class="col-12 col-md-6">

                            <label for="vehicle_leave" class="form-label fw-semibold">
                                Saída do veículo
                            </label>

                            <input type="datetime-local" id="vehicle_leave" name="vehicle_leave" class="form-control"
                                value="{{ old('vehicle_leave') }}">

                            <small class="text-secondary">
                                Pode ser preenchido posteriormente.
                            </small>

                        </div>

                    </div>

                </div>

            </div>


            {{-- DESCRIÇÃO --}}
            <div class="card border-0 shadow-sm mb-4">

                <div class="card-header bg-white border-0 pt-4 px-4">

                    <h5 class="fw-bold mb-1">
                        <i class="fa-solid fa-clipboard-list me-2" style="color: #ff6500;"></i>
                        Descrição do serviço
                    </h5>

                    <small class="text-secondary">
                        Descreva o problema informado pelo cliente ou o serviço solicitado.
                    </small>

                </div>


                <div class="card-body p-4">

                    <textarea class="form-control" id="description" name="description" rows="5"
                        placeholder="Descreva o serviço solicitado..." oninput="this.value = this.value.toUpperCase();">{{ old('description') }}</textarea>

                    @error('description')
                        <div class="text-danger small mt-1">
                            {{ $message }}
                        </div>
                    @enderror

                </div>

            </div>


            {{-- AÇÕES --}}
            <div class="d-flex flex-column flex-sm-row justify-content-end gap-2 mb-4">

                <a href="{{ route('service-orders.index') }}" class="btn btn-outline-secondary px-4">
                    <i class="fa-solid fa-arrow-left me-2"></i>
                    Cancelar
                </a>

                <button type="submit" class="btn text-white fw-semibold px-4" style="background-color: #ff6500;">
                    <i class="fa-solid fa-check me-2"></i>
                    Criar Ordem de Serviço
                </button>

            </div>

        </form>

    </div>

@endsection
