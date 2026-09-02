<div>

    {{-- CABEÇALHO --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">

        <div>
            <h2 class="fw-bold mb-1">
                <i class="fa-solid fa-clipboard-list me-2" style="color: #ff6500;"></i>
                Ordens de Serviço
            </h2>

            <p class="text-secondary mb-0">
                Gerencie e acompanhe as ordens de serviço da sua empresa.
            </p>
        </div>

        <div class="mt-3 mt-md-0">
            <a href="{{ route('service-orders.create') }}" class="btn text-white fw-semibold px-4"
                style="background-color: #ff6500;">
                <i class="bi bi-file-earmark-plus"></i>
                Nova OS
            </a>
        </div>

    </div>

    {{-- RESUMO --}}
    <div class="row g-3 mb-4">

        <div class="col-12 col-sm-6 col-xl-3">

            <div class="card border-0 shadow-sm h-100">

                <div class="card-body d-flex align-items-center">

                    <div class="rounded-3 d-flex align-items-center justify-content-center me-3"
                        style="width: 50px; height: 50px; background-color: #fff1e6;">
                        <i class="bi bi-clipboard-check" style="color:#ff6500"></i>
                    </div>

                    <div>
                        <small class="text-secondary">
                            Total de OS
                        </small>

                        <h4 class="fw-bold mb-0">
                            {{ $serviceOrders->total() }}
                        </h4>
                    </div>

                </div>

            </div>

        </div>

    </div>

    {{-- FILTROS --}}
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-body">

            <div class="row g-3">

                <div class="col-md-8">

                    <label class="form-label fw-semibold">
                        Search
                    </label>

                    <div class="input-group">

                        <span class="input-group-text bg-light">
                            <i class="bi bi-search"></i>
                        </span>

                        <input type="text" class="form-control" wire:model.live.debounce.300ms="search"
                            placeholder="Nome do cliente ou placa do veículo...">

                    </div>

                </div>


                <div class="col-md-4">

                    <label class="form-label fw-semibold">
                        Status
                    </label>

                    <select class="form-select" wire:model.live="status">

                        <option value="">
                            All statuses
                        </option>

                        <option value="open">
                            Open
                        </option>

                        <option value="in_progress">
                            In progress
                        </option>

                        <option value="finished">
                            Finished
                        </option>

                        <option value="cancelled">
                            Cancelled
                        </option>

                    </select>

                </div>

            </div>

        </div>

    </div>

    {{-- LISTA --}}
    <div class="row g-4">

        {{-- CARD NOVA OS --}}
        <div class="col-12 col-sm-6 col-lg-4 col-xl-3">

            <div class="card h-100 border-2 border-dashed shadow-sm" style="border-color: #ff6500 !important;">

                <div class="card-body d-flex flex-column align-items-center justify-content-center text-center py-5">

                    <div class="rounded-circle d-flex align-items-center justify-content-center mb-3"
                        style="width: 75px; height: 75px; background-color: #fff1e6;">
                        <i class="bi bi-plus fs-3" style="color: #ff6500;"></i>
                    </div>

                    <h5 class="fw-bold mb-2">
                        Nova Ordem de Serviço
                    </h5>

                    <p class="text-secondary small mb-4">
                        Abra uma nova OS para registrar um atendimento.
                    </p>

                    <a href="{{ route('service-orders.create') }}" class="btn text-white px-4"
                        style="background-color: #ff6500;">
                        <i class="bi bi-file-earmark-plus me-2"></i>
                        Criar OS
                    </a>

                </div>

            </div>

        </div>


        {{-- ORDENS --}}
        @forelse ($serviceOrders as $order)
            <div class="col-12 col-sm-6 col-lg-4 col-xl-3">

                <div class="card h-100 border-0 shadow-sm card-hover">

                    {{-- TOPO --}}
                    <div class="card-body">

                        <div class="d-flex justify-content-between align-items-start mb-3">

                            <div class="rounded-3 d-flex align-items-center justify-content-center"
                                style="width: 50px; height: 50px; background-color: #f1f3f5;">
                                <i class="bi bi-car-front" style="color:#ff6500"></i>
                            </div>

                            <label for="customer_name" class="form-label fw-semibold">
                                {{ implode(' ', array_slice(explode(' ', trim($order->customer_name)), 0, 2)) }}
                            </label>


                            {{-- STATUS --}}
                            @php
                                $status = match ($order->status) {
                                    'open' => ['label' => 'Open', 'class' => 'primary', 'icon' => 'folder2-open'],
                                    'in_progress' => [
                                        'label' => 'In progress',
                                        'class' => 'warning',
                                        'icon' => 'arrow-repeat',
                                    ],
                                    'finished' => [
                                        'label' => 'Finished',
                                        'class' => 'success',
                                        'icon' => 'check-circle',
                                    ],
                                    'cancelled' => [
                                        'label' => 'Cancelled',
                                        'class' => 'danger',
                                        'icon' => 'x-circle',
                                    ],
                                    default => [
                                        'label' => 'Unknown',
                                        'class' => 'secondary',
                                        'icon' => 'question-circle',
                                    ],
                                };
                            @endphp

                            <span class="badge text-bg-{{ $status['class'] }}">
                                <i class="bi bi-{{ $status['icon'] }} me-1"></i>
                                {{ $status['label'] }}
                            </span>

                        </div>


                        {{-- OS --}}
                        <div class="mb-3">

                            <small class="text-secondary">
                                Ordem de Serviço
                            </small>

                            <h5 class="fw-bold mb-1">
                                #{{ $order->id }}
                            </h5>


                        </div>


                        {{-- VEÍCULO --}}
                        <div class="d-flex align-items-center">

                            <i class="fa-solid fa-car-side text-secondary me-2"></i>

                            <div>

                                <small class="text-secondary d-block">
                                    Veículo
                                </small>

                                <span class="fw-semibold">
                                    {{ $order->vehicle_plate ?: 'Não informado' }}
                                </span>

                            </div>

                        </div>

                    </div>


                    {{-- FOOTER --}}
                    <div class="card-footer bg-white border-0 pt-0 pb-3 px-3">

                        <a href="{{ route('service-orders.show', $order->id) }}"
                            class="btn btn-outline-secondary w-100">
                            <i class="fa-solid fa-eye me-2"></i>
                            Ver detalhes
                        </a>

                    </div>

                </div>

            </div>

        @empty

            <div class="col-12">

                <div class="card border-0 shadow-sm">

                    <div class="card-body text-center py-5">

                        <i class="fa-solid fa-clipboard-list fs-1 text-secondary mb-3"></i>

                        <h5 class="fw-bold">
                            Nenhuma ordem de serviço
                        </h5>

                        <p class="text-secondary">
                            Você ainda não possui ordens de serviço cadastradas.
                        </p>

                        <a href="{{ route('service-orders.create') }}" class="btn text-white"
                            style="background-color: #ff6500;">
                            <i class="fa-solid fa-plus me-2"></i>
                            Criar primeira OS
                        </a>

                    </div>

                </div>

            </div>
        @endforelse

    </div>


    {{-- PAGINATION --}}
    <div class="mt-3">
        {{ $serviceOrders->links() }}
    </div>



</div>
