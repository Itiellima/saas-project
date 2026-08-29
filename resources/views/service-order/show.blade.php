@extends('layouts.main')

@section('title', 'OS #' . $serviceOrder->id . ' - OficinaOS')

@section('content')

    <div class="container-fluid px-3 px-md-4 py-4">

        @php
            $statusConfig = match ($serviceOrder->status) {
                'open' => [
                    'class' => 'primary',
                    'icon' => 'bi-folder2-open',
                    'label' => 'Aberta',
                ],

                'in_progress' => [
                    'class' => 'warning',
                    'icon' => 'bi-arrow-repeat',
                    'label' => 'Em andamento',
                ],

                'finished' => [
                    'class' => 'success',
                    'icon' => 'bi-check-circle',
                    'label' => 'Finalizada',
                ],

                'cancelled' => [
                    'class' => 'danger',
                    'icon' => 'bi-x-circle',
                    'label' => 'Cancelada',
                ],

                default => [
                    'class' => 'secondary',
                    'icon' => 'fa-question',
                    'label' => 'Desconhecido',
                ],
            };
        @endphp


        {{-- CABEÇALHO --}}
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">

            <div>

                <div class="d-flex align-items-center gap-3 mb-2">

                    <div class="rounded-3 d-flex align-items-center justify-content-center"
                        style="width: 50px; height: 50px; background-color: #fff1e6;">
                        <i class="fa-solid fa-file-invoice fs-5" style="color: #ff6500;"></i>
                    </div>

                    <div>

                        <h3 class="fw-bold mb-0">
                            Ordem de Serviço #{{ $serviceOrder->id }}
                        </h3>

                        <small class="text-secondary">
                            Detalhes e gerenciamento da ordem de serviço
                        </small>

                    </div>

                </div>

            </div>


            <div class="d-flex gap-2 mt-3 mt-md-0">

                <button type="button" class="btn btn-outline-secondary" onclick="window.print()">
                    <i class="fa-solid fa-print me-2"></i>
                    Imprimir
                </button>

                <span class="badge text-bg-{{ $statusConfig['class'] }} d-flex align-items-center px-3">
                    <i class="fa-solid {{ $statusConfig['icon'] }} me-2"></i>
                    {{ strtoupper($statusConfig['label']) }}
                </span>

            </div>

        </div>


        {{-- INFORMAÇÕES PRINCIPAIS --}}
        <div class="row g-4 mb-4">

            {{-- CLIENTE --}}
            <div class="col-12 col-lg-6">

                <div class="card border-0 shadow-sm h-100">

                    <div class="card-header bg-white border-0 pt-4 px-4">

                        <h5 class="fw-bold mb-1">
                            <i class="fa-solid fa-user me-2" style="color: #ff6500;"></i>
                            Cliente
                        </h5>

                    </div>


                    <div class="card-body px-4">

                        <div class="row g-3">

                            <div class="col-12">

                                <small class="text-secondary d-block">
                                    Nome
                                </small>

                                <span class="fw-semibold">
                                    {{ $serviceOrder->customer_name }}
                                </span>

                            </div>


                            <div class="col-12 col-md-6">

                                <small class="text-secondary d-block">
                                    Telefone
                                </small>

                                <span class="fw-semibold">
                                    {{ $serviceOrder->customer_phone ?: 'Não informado' }}
                                </span>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- VEÍCULO --}}
            <div class="col-12 col-lg-6">

                <div class="card border-0 shadow-sm h-100">

                    <div class="card-header bg-white border-0 pt-4 px-4">

                        <h5 class="fw-bold mb-1">
                            <i class="fa-solid fa-car me-2" style="color: #ff6500;"></i>
                            Veículo
                        </h5>

                    </div>


                    <div class="card-body px-4">

                        <div class="row g-3">

                            <div class="col-12 col-md-4">

                                <small class="text-secondary d-block">
                                    Placa
                                </small>

                                <span class="fw-bold">
                                    {{ $serviceOrder->vehicle_plate }}
                                </span>

                            </div>


                            <div class="col-12 col-md-4">

                                <small class="text-secondary d-block">
                                    Modelo
                                </small>

                                <span class="fw-semibold">
                                    {{ $serviceOrder->vehicle_model ?: 'Não informado' }}
                                </span>

                            </div>


                            <div class="col-12 col-md-4">

                                <small class="text-secondary d-block">
                                    Quilometragem
                                </small>

                                <span class="fw-semibold">
                                    {{ $serviceOrder->vehicle_km ? number_format($serviceOrder->vehicle_km, 0, ',', '.') . ' km' : 'Não informado' }}
                                </span>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- ENTRADA / SAÍDA / TOTAL --}}
        <div class="row g-4 mb-4">

            <div class="col-12 col-md-4">

                <div class="card border-0 shadow-sm h-100">

                    <div class="card-body d-flex align-items-center">

                        <div class="rounded-3 d-flex align-items-center justify-content-center me-3"
                            style="width: 50px; height: 50px; background-color: #f1f3f5;">
                            <i class="fa-solid fa-right-to-bracket text-secondary"></i>
                        </div>

                        <div>

                            <small class="text-secondary d-block">
                                Entrada
                            </small>

                            <strong>
                                {{ $serviceOrder->vehicle_enter?->format('d/m/Y H:i') ?? 'Não informado' }}
                            </strong>

                        </div>

                    </div>

                </div>

            </div>


            <div class="col-12 col-md-4">

                <div class="card border-0 shadow-sm h-100">

                    <div class="card-body d-flex align-items-center">

                        <div class="rounded-3 d-flex align-items-center justify-content-center me-3"
                            style="width: 50px; height: 50px; background-color: #f1f3f5;">
                            <i class="fa-solid fa-right-from-bracket text-secondary"></i>
                        </div>

                        <div>

                            <small class="text-secondary d-block">
                                Saída
                            </small>

                            <strong>
                                {{ $serviceOrder->vehicle_leave?->format('d/m/Y H:i') ?? 'Ainda não saiu' }}
                            </strong>

                        </div>

                    </div>

                </div>

            </div>


            <div class="col-12 col-md-4">

                <div class="card border-0 shadow-sm h-100">

                    <div class="card-body d-flex align-items-center">

                        <div class="rounded-3 d-flex align-items-center justify-content-center me-3"
                            style="width: 50px; height: 50px; background-color: #fff1e6;">
                            <i class="fa-solid fa-dollar-sign" style="color: #ff6500;"></i>
                        </div>

                        <div>

                            <small class="text-secondary d-block">
                                Total da OS
                            </small>

                            <h4 class="fw-bold mb-0">
                                R$ {{ number_format($serviceOrder->total ?? 0, 2, ',', '.') }}
                            </h4>

                        </div>

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

            </div>


            <div class="card-body px-4">

                <p class="mb-0 text-secondary">

                    {{ $serviceOrder->description ?: 'Nenhuma descrição foi informada.' }}

                </p>

            </div>

        </div>


        {{-- ITENS DA OS --}}
        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-white border-0 pt-4 px-4">

                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center">

                    <div>

                        <h5 class="fw-bold mb-1">
                            <i class="fa-solid fa-boxes-stacked me-2" style="color: #ff6500;"></i>
                            Serviços e produtos
                        </h5>

                        <small class="text-secondary">
                            Adicione os serviços realizados e os produtos utilizados.
                        </small>

                    </div>

                </div>

            </div>


            <div class="card-body p-4">

                <fieldset @disabled($serviceOrder->status === 'finished')>

                    <livewire:service-order.item-form :serviceOrder="$serviceOrder" />

                    <hr class="my-4">

                    <livewire:service-order.item-list :serviceOrder="$serviceOrder" />

                </fieldset>

            </div>

        </div>


        {{-- AÇÕES --}}
        <div class="card border-0 shadow-sm">

            <div class="card-body p-3">

                <div class="d-flex flex-column flex-md-row gap-2 justify-content-between">

                    {{-- VOLTAR --}}
                    <a href="{{ route('service-orders.index') }}" class="btn btn-outline-secondary">
                        <i class="fa-solid fa-arrow-left me-2"></i>
                        Voltar para OS
                    </a>


                    <div class="d-flex flex-column flex-sm-row gap-2">

                        {{-- FECHAR --}}
                        <form action="{{ route('service-orders.close', $serviceOrder->id) }}" method="POST">

                            @csrf
                            @method('PUT')

                            <button type="submit" class="btn btn-warning w-100" @disabled($serviceOrder->status === 'finished')>
                                <i class="fa-solid fa-lock me-2"></i>
                                Finalizar OS
                            </button>

                        </form>


                        {{-- EXCLUIR --}}
                        <form action="{{ route('service-orders.destroy', $serviceOrder->id) }}" method="POST">

                            @csrf
                            @method('DELETE')

                            <button type="submit" class="btn btn-outline-danger" @disabled($serviceOrder->status === 'finished')
                                onclick="return confirm('Tem certeza que deseja excluir esta ordem de serviço?')">
                                <i class="fa-solid fa-trash me-2"></i>
                                Excluir OS
                            </button>

                        </form>

                    </div>

                </div>

            </div>

        </div>

    </div>


    @push('scripts')
        <script>
            document.addEventListener('livewire:init', () => {

                function initItemSelect() {

                    const select = document.querySelector('#item');

                    if (!select) {
                        return;
                    }

                    if (select.tomselect) {
                        select.tomselect.destroy();
                    }

                    new TomSelect(select, {
                        create: true,
                        sortField: {
                            field: 'text',
                            direction: 'asc'
                        }
                    });

                }


                initItemSelect();


                Livewire.on('item-added', () => {

                    setTimeout(() => {
                        initItemSelect();
                    }, 50);

                });

            });
        </script>
    @endpush

@endsection
