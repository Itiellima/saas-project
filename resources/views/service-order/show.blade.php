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
                        <i class="bi-file-earmark fs-5" style="color: #ff6500;"></i>
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
                    <i class="bi-printer me-2"></i>
                    Imprimir
                </button>

                <span class="badge text-bg-{{ $statusConfig['class'] }} d-flex align-items-center px-3">
                    <i class="bi {{ $statusConfig['icon'] }} me-2"></i>
                    {{ strtoupper($statusConfig['label']) }}
                </span>

            </div>

        </div>


        {{-- AVISO DE REABERTURA --}}
        @if ($serviceOrder->reopened_at)
            <div class="alert alert-warning d-flex align-items-start gap-2 mb-4">
                <i class="bi bi-exclamation-triangle-fill fs-5"></i>
                <div>
                    <strong>
                        Esta OS foi reaberta em {{ $serviceOrder->reopened_at->format('d/m/Y H:i') }}.
                    </strong>
                    <div>Motivo: {{ $serviceOrder->reopen_reason }}</div>
                </div>
            </div>
        @endif


        {{-- INFORMAÇÕES PRINCIPAIS --}}
        <div class="row g-4 mb-4">

            {{-- CLIENTE --}}
            <div class="col-12 col-lg-6">

                <div class="card border-0 shadow-sm h-100">

                    <div class="card-header bg-white border-0 pt-4 px-4">

                        <h5 class="fw-bold mb-1">
                            <i class="bi-person me-2" style="color: #ff6500;"></i>
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
                            <i class="bi-car-front me-2" style="color: #ff6500;"></i>
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
                            <i class="bi-arrow-bar-right text-secondary"></i>
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
                            <i class="bi-arrow-bar-left text-secondary"></i>
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
                            <i class="bi-currency-dollar" style="color: #ff6500;"></i>
                        </div>

                        <div>

                            <small class="text-secondary d-block">
                                Total da OS
                            </small>

                            <livewire:service-order.total :serviceOrder="$serviceOrder" />

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- DESCRIÇÃO --}}
        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-white border-0 pt-4 px-4">

                <h5 class="fw-bold mb-1">
                    <i class="bi-card-text me-2" style="color: #ff6500;"></i>
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
                            <i class="bi-boxes me-2" style="color: #ff6500;"></i>
                            Serviços e produtos
                        </h5>

                        <small class="text-secondary">
                            Adicione os serviços realizados e os produtos utilizados.
                        </small>

                    </div>

                </div>

            </div>


            <div class="card-body p-4">

                <fieldset @disabled($serviceOrder->isLocked())>

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
                        <i class="bi-arrow-left me-2"></i>
                        Voltar para OS
                    </a>


                    <div class="d-flex flex-column flex-sm-row gap-2">

                        @if ($serviceOrder->status === 'finished')
                            {{-- REABRIR --}}
                            <button type="button" class="btn btn-outline-warning" data-bs-toggle="modal"
                                data-bs-target="#reopenModal">
                                <i class="bi-unlock me-2"></i>
                                Reabrir OS
                            </button>
                        @else
                            {{-- FECHAR --}}
                            <form action="{{ route('service-orders.close', $serviceOrder->id) }}" method="POST">

                                @csrf
                                @method('PUT')

                                <button type="submit" class="btn btn-warning w-100" @disabled($serviceOrder->isLocked())>
                                    <i class="bi-lock me-2"></i>
                                    Finalizar OS
                                </button>

                            </form>
                        @endif


                        {{-- EXCLUIR --}}
                        <form action="{{ route('service-orders.destroy', $serviceOrder->id) }}" method="POST">

                            @csrf
                            @method('DELETE')

                            <button type="submit" class="btn btn-outline-danger" @disabled($serviceOrder->isLocked())
                                onclick="return confirm('Tem certeza que deseja excluir esta ordem de serviço?')">
                                <i class="bi-trash me-2"></i>
                                Excluir OS
                            </button>

                        </form>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- MODAL REABRIR --}}
    @if ($serviceOrder->status === 'finished')
        <div class="modal fade" id="reopenModal" tabindex="-1" aria-labelledby="reopenModalLabel" aria-hidden="true">

            <div class="modal-dialog modal-dialog-centered">

                <form action="{{ route('service-orders.reopen', $serviceOrder->id) }}" method="POST"
                    class="modal-content">

                    @csrf
                    @method('PUT')

                    <div class="modal-header">
                        <h5 class="modal-title fw-bold" id="reopenModalLabel">
                            <i class="bi bi-exclamation-triangle-fill text-danger me-2"></i>
                            Reabrir OS #{{ $serviceOrder->id }}
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                    </div>


                    <div class="modal-body">

                        <div class="alert alert-danger">
                            <strong>Atenção: esta OS já foi finalizada
                                {{ $serviceOrder->finished_at ? 'em ' . $serviceOrder->finished_at->format('d/m/Y H:i') : '' }}.</strong>

                            <ul class="mb-0 mt-2 ps-3">
                                <li>Ela voltará a ficar <strong>editável</strong>: itens e valores poderão ser alterados.</li>
                                <li>O total pode ficar <strong>diferente do que já foi cobrado</strong> ou entregue ao cliente.</li>
                                <li>A reabertura e o motivo ficarão <strong>registrados</strong> e visíveis nesta OS.</li>
                            </ul>
                        </div>


                        <div class="mb-3">

                            <label for="reopen_reason" class="form-label fw-semibold">
                                Motivo da reabertura
                            </label>

                            <textarea id="reopen_reason" name="reopen_reason" rows="3" minlength="10" maxlength="255" required
                                class="form-control @error('reopen_reason') is-invalid @enderror"
                                placeholder="Ex.: item esquecido na finalização">{{ old('reopen_reason') }}</textarea>

                            @error('reopen_reason')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                        </div>


                        <div class="form-check">

                            <input type="checkbox" id="reopen_confirm" name="reopen_confirm" value="1" required
                                class="form-check-input @error('reopen_confirm') is-invalid @enderror">

                            <label for="reopen_confirm" class="form-check-label">
                                Entendo que esta OS já foi finalizada e que a reabertura ficará registrada.
                            </label>

                            @error('reopen_confirm')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                        </div>

                    </div>


                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                            Cancelar
                        </button>

                        <button type="submit" id="reopen_submit" class="btn btn-danger" disabled>
                            <i class="bi-unlock me-2"></i>
                            Reabrir mesmo assim
                        </button>
                    </div>

                </form>

            </div>

        </div>


        @push('scripts')
            <script>
                document.addEventListener('DOMContentLoaded', () => {

                    const reason = document.getElementById('reopen_reason');
                    const confirmBox = document.getElementById('reopen_confirm');
                    const submit = document.getElementById('reopen_submit');

                    // Só libera o botão com o motivo preenchido e a caixa marcada
                    const toggleSubmit = () => {
                        submit.disabled = !(confirmBox.checked && reason.value.trim().length >= 10);
                    };

                    reason.addEventListener('input', toggleSubmit);
                    confirmBox.addEventListener('change', toggleSubmit);
                    toggleSubmit();

                    // Reabre o modal se a validação falhou
                    @if ($errors->has('reopen_reason') || $errors->has('reopen_confirm'))
                        bootstrap.Modal.getOrCreateInstance(document.getElementById('reopenModal')).show();
                    @endif

                });
            </script>
        @endpush
    @endif

@endsection
