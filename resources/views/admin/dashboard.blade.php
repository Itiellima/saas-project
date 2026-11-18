@extends('layouts.main')

@section('title', 'Administração - OficinaOS')
@section('page_title', 'Administração')

@section('content')

    <div class="container-fluid px-3 px-md-4 py-4">

        {{-- CABEÇALHO --}}
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">

            <div>
                <h3 class="fw-bold mb-0">
                    <i class="bi bi-shield-lock me-2" style="color: #ff6500;"></i>
                    Administração
                </h3>
                <small class="text-secondary">
                    Visão geral da plataforma em {{ now()->translatedFormat('F \d\e Y') }}
                </small>
            </div>

            <a href="{{ route('admin.tenants.index') }}" class="btn text-white fw-semibold px-4"
                style="background-color: #ff6500;">
                <i class="bi bi-buildings me-2"></i>
                Ver oficinas
            </a>

        </div>


        {{-- INDICADORES --}}
        <div class="row g-3 mb-4">

            @php
                $cards = [
                    [
                        'label' => 'Oficinas',
                        'value' => $stats['tenants'],
                        'icon' => 'bi-buildings',
                        'detail' => $stats['active'] . ' ativas · ' . $stats['suspended'] . ' suspensas',
                    ],
                    [
                        'label' => 'Novas no mês',
                        'value' => $stats['new_this_month'],
                        'icon' => 'bi-person-plus',
                        'detail' => 'Cadastros desde o dia 1º',
                    ],
                    [
                        'label' => 'OS criadas no mês',
                        'value' => $stats['orders_this_month'],
                        'icon' => 'bi-clipboard-data',
                        'detail' => 'Somando todas as oficinas',
                    ],
                ];
            @endphp

            @foreach ($cards as $card)
                <div class="col-12 col-md-4">

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


        {{-- ÚLTIMOS CADASTROS --}}
        <div class="card border-0 shadow-sm">

            <div class="card-header bg-white border-0 pt-4 px-4">
                <h5 class="fw-bold mb-1">
                    <i class="bi bi-clock-history me-2" style="color: #ff6500;"></i>
                    Últimos cadastros
                </h5>
                <small class="text-secondary">As 5 oficinas cadastradas mais recentemente.</small>
            </div>

            <div class="list-group list-group-flush px-2 pb-2">

                @forelse ($latestTenants as $tenant)
                    @php($status = $tenant->statusConfig())

                    <a href="{{ route('admin.tenants.show', $tenant) }}"
                        class="list-group-item list-group-item-action d-flex justify-content-between align-items-center gap-2">

                        <div class="min-w-0">
                            <div class="fw-semibold text-truncate">{{ $tenant->name }}</div>
                            <small class="text-secondary">
                                {{ $tenant->owner?->email ?? 'Sem usuário' }}
                                · {{ $tenant->created_at->diffForHumans() }}
                            </small>
                        </div>

                        <span class="badge text-bg-{{ $status['class'] }} flex-shrink-0">{{ $status['label'] }}</span>

                    </a>
                @empty
                    <div class="text-center text-secondary py-4">
                        <i class="bi bi-buildings fs-2 d-block mb-1"></i>
                        Nenhuma oficina cadastrada ainda
                    </div>
                @endforelse

            </div>

        </div>

    </div>

@endsection
