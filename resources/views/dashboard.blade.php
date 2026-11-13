@extends('layouts.main')

@section('title', 'Dashboard - OficinaOS')
@section('page_title', 'Dashboard')

@section('content')

    <div class="container-fluid px-3 px-md-4 py-4">

        {{-- CABEÇALHO --}}
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">

            <div>
                <h3 class="fw-bold mb-0">
                    Olá, {{ \Illuminate\Support\Str::of(Auth::user()->name)->explode(' ')->first() }}!
                </h3>

                <small class="text-secondary">
                    Resumo de {{ now()->translatedFormat('F \d\e Y') }}
                </small>
            </div>

            <a href="{{ route('service-orders.create') }}" class="btn text-white fw-semibold px-4"
                style="background-color: #ff6500;">
                <i class="bi bi-plus-lg me-2"></i>
                Nova OS
            </a>

        </div>


        {{-- INDICADORES --}}
        <div class="row g-3 mb-4">

            @php
                $cards = [
                    [
                        'label' => 'OS em aberto',
                        'value' => $stats['pending'],
                        'icon' => 'bi-folder2-open',
                        'detail' => $stats['open'] . ' abertas · ' . $stats['in_progress'] . ' em andamento',
                    ],
                    [
                        'label' => 'Finalizadas no mês',
                        'value' => $stats['finished'],
                        'icon' => 'bi-clipboard-check',
                        'detail' => 'OS concluídas desde o dia 1º',
                    ],
                    [
                        'label' => 'Faturamento do mês',
                        'value' => 'R$ ' . number_format($stats['revenue'], 2, ',', '.'),
                        'icon' => 'bi-currency-dollar',
                        'detail' => 'Soma das OS finalizadas',
                    ],
                    [
                        'label' => 'Ticket médio',
                        'value' => 'R$ ' . number_format($stats['average_ticket'], 2, ',', '.'),
                        'icon' => 'bi-graph-up',
                        'detail' => 'Faturamento ÷ OS finalizadas',
                    ],
                ];
            @endphp

            @foreach ($cards as $card)
                <div class="col-12 col-sm-6 col-xl-3">

                    <div class="card border-0 shadow-sm h-100">

                        <div class="card-body d-flex align-items-start gap-3">

                            <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0"
                                style="width: 48px; height: 48px; background-color: #fff1e6;">
                                <i class="bi {{ $card['icon'] }} fs-5" style="color: #ff6500;"></i>
                            </div>

                            <div class="min-w-0">
                                <small class="text-secondary d-block">{{ $card['label'] }}</small>
                                <h4 class="fw-bold mb-0 text-truncate">{{ $card['value'] }}</h4>
                                <small class="text-secondary">{{ $card['detail'] }}</small>
                            </div>

                        </div>

                    </div>

                </div>
            @endforeach

        </div>


        <div class="row g-4 mb-4">

            {{-- OS EM ABERTO HÁ MAIS TEMPO --}}
            <div class="col-12 col-lg-7">

                <div class="card border-0 shadow-sm h-100">

                    <div class="card-header bg-white border-0 pt-4 px-4">
                        <h5 class="fw-bold mb-1">
                            <i class="bi bi-hourglass-split me-2" style="color: #ff6500;"></i>
                            OS em aberto há mais tempo
                        </h5>
                        <small class="text-secondary">Veículos que estão há mais tempo na oficina.</small>
                    </div>

                    <div class="list-group list-group-flush px-2 pb-2">

                        @forelse ($oldestPending as $order)
                            @php($status = $order->statusConfig())

                            <a href="{{ route('service-orders.show', $order->id) }}"
                                class="list-group-item list-group-item-action d-flex justify-content-between align-items-center gap-2">

                                <div class="min-w-0">
                                    <div class="fw-semibold text-truncate">
                                        #{{ $order->id }} · {{ $order->customer_name }}
                                    </div>
                                    <small class="text-secondary">
                                        {{ $order->vehicle_plate ?: 'Sem placa' }}
                                        @if ($order->vehicle_enter)
                                            · entrou {{ $order->vehicle_enter->diffForHumans() }}
                                        @endif
                                    </small>
                                </div>

                                <span class="badge text-bg-{{ $status['class'] }} flex-shrink-0">
                                    {{ $status['label'] }}
                                </span>

                            </a>
                        @empty
                            <div class="text-center text-secondary py-4">
                                <i class="bi bi-check2-circle fs-2 d-block mb-1"></i>
                                Nenhuma OS em aberto 🎉
                            </div>
                        @endforelse

                    </div>

                </div>

            </div>


            {{-- ESTOQUE BAIXO --}}
            <div class="col-12 col-lg-5">

                <div class="card border-0 shadow-sm h-100">

                    <div class="card-header bg-white border-0 pt-4 px-4">
                        <h5 class="fw-bold mb-1">
                            <i class="bi bi-box-seam me-2" style="color: #ff6500;"></i>
                            Estoque baixo
                        </h5>
                        <small class="text-secondary">
                            Produtos com {{ \App\Http\Controllers\DashboardController::LOW_STOCK_LIMIT }} unidades ou menos.
                        </small>
                    </div>

                    <div class="list-group list-group-flush px-2 pb-2">

                        @forelse ($lowStockItems as $item)
                            <a href="{{ route('items.edit', $item->id) }}"
                                class="list-group-item list-group-item-action d-flex justify-content-between align-items-center gap-2">

                                <span class="fw-semibold text-truncate">{{ $item->name }}</span>

                                <span class="badge flex-shrink-0 {{ $item->stock <= 0 ? 'text-bg-danger' : 'text-bg-warning' }}">
                                    {{ $item->stock <= 0 ? 'Sem estoque' : $item->stock . ' em estoque' }}
                                </span>

                            </a>
                        @empty
                            <div class="text-center text-secondary py-4">
                                <i class="bi bi-check2-circle fs-2 d-block mb-1"></i>
                                Nenhum produto com estoque baixo
                            </div>
                        @endforelse

                    </div>

                </div>

            </div>

        </div>


        {{-- ÚLTIMAS OS --}}
        <div class="card border-0 shadow-sm">

            <div class="card-header bg-white border-0 pt-4 px-4 d-flex justify-content-between align-items-center">

                <div>
                    <h5 class="fw-bold mb-1">
                        <i class="bi bi-clock-history me-2" style="color: #ff6500;"></i>
                        Últimas OS
                    </h5>
                    <small class="text-secondary">As 5 ordens de serviço mais recentes.</small>
                </div>

                <a href="{{ route('service-orders.index') }}" class="btn btn-sm btn-outline-secondary">
                    Ver todas
                </a>

            </div>

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">OS</th>
                            <th>Cliente</th>
                            <th>Placa</th>
                            <th>Status</th>
                            <th class="text-end pe-4">Total</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($latestOrders as $order)
                            @php($status = $order->statusConfig())

                            <tr>
                                <td class="ps-4">
                                    <a href="{{ route('service-orders.show', $order->id) }}" class="fw-semibold">
                                        #{{ $order->id }}
                                    </a>
                                </td>
                                <td>{{ $order->customer_name }}</td>
                                <td>{{ $order->vehicle_plate ?: '—' }}</td>
                                <td>
                                    <span class="badge text-bg-{{ $status['class'] }}">{{ $status['label'] }}</span>
                                </td>
                                <td class="text-end pe-4">R$ {{ number_format($order->total, 2, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-secondary py-4">
                                    Nenhuma OS cadastrada ainda.
                                    <a href="{{ route('service-orders.create') }}">Criar a primeira OS</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>

            </div>

        </div>

    </div>

@endsection
