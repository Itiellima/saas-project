@extends('layouts.main')

@section('title', $tenant->name . ' - OficinaOS')
@section('page_title', 'Oficinas')

@section('content')

    @php
        $status = $tenant->statusConfig();
    @endphp

    <div class="container-fluid px-3 px-md-4 py-4">

        <a href="{{ route('admin.tenants.index') }}" class="text-decoration-none small text-secondary">
            <i class="bi bi-arrow-left me-1"></i>
            Voltar para oficinas
        </a>


        {{-- CABEÇALHO --}}
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mt-2 mb-4">

            <div>
                <h3 class="fw-bold mb-1">
                    {{ $tenant->name }}
                    <span class="badge text-bg-{{ $status['class'] }} fs-6 align-middle">{{ $status['label'] }}</span>
                </h3>
                <small class="text-secondary">Cadastrada em {{ $tenant->created_at->format('d/m/Y \à\s H:i') }}</small>
            </div>

            <div>
                @if ($tenant->isSuspended())
                    <form action="{{ route('admin.tenants.reactivate', $tenant) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <button type="submit" class="btn btn-success"
                            onclick="return confirm('Reativar esta oficina? Os usuários dela voltam a conseguir entrar.')">
                            <i class="bi bi-arrow-counterclockwise me-1"></i>
                            Reativar
                        </button>
                    </form>
                @elseif ($tenant->canBeSuspended())
                    <form action="{{ route('admin.tenants.suspend', $tenant) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <button type="submit" class="btn btn-outline-danger"
                            onclick="return confirm('Suspender esta oficina? Os usuários dela serão desconectados e não conseguirão entrar até você reativar.')">
                            <i class="bi bi-slash-circle me-1"></i>
                            Suspender
                        </button>
                    </form>
                @else
                    <span class="badge text-bg-light border text-secondary p-2">
                        <i class="bi bi-shield-lock me-1"></i>
                        Oficina do administrador geral
                    </span>
                @endif
            </div>

        </div>


        {{-- NÚMEROS --}}
        <div class="row g-3 mb-4">

            @php
                $cards = [
                    ['label' => 'OS no total', 'value' => $stats['orders'], 'icon' => 'bi-clipboard-data'],
                    ['label' => 'OS no mês', 'value' => $stats['orders_this_month'], 'icon' => 'bi-calendar-check'],
                    ['label' => 'Itens cadastrados', 'value' => $stats['items'], 'icon' => 'bi-boxes'],
                    [
                        'label' => 'Última OS',
                        'value' => $stats['last_order_at']
                            ? \Illuminate\Support\Carbon::parse($stats['last_order_at'])->diffForHumans()
                            : 'Nenhuma',
                        'icon' => 'bi-clock-history',
                    ],
                ];
            @endphp

            @foreach ($cards as $card)
                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body d-flex align-items-center gap-3">
                            <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0"
                                style="width: 48px; height: 48px; background-color: #fff1e6;">
                                <i class="bi {{ $card['icon'] }} fs-5" style="color: #ff6500;"></i>
                            </div>
                            <div class="min-w-0">
                                <small class="text-secondary d-block">{{ $card['label'] }}</small>
                                <h4 class="fw-bold mb-0 text-truncate">{{ $card['value'] }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

        </div>


        <div class="row g-4">

            {{-- DADOS --}}
            <div class="col-12 col-lg-5">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white border-0 pt-4 px-4">
                        <h5 class="fw-bold mb-0">
                            <i class="bi bi-building me-2" style="color: #ff6500;"></i>
                            Dados da oficina
                        </h5>
                    </div>
                    <div class="card-body px-4">
                        <dl class="row mb-0 small">
                            <dt class="col-5 text-secondary fw-normal">E-mail</dt>
                            <dd class="col-7">{{ $tenant->email ?: '—' }}</dd>

                            <dt class="col-5 text-secondary fw-normal">Telefone</dt>
                            <dd class="col-7">{{ $tenant->phone ?: '—' }}</dd>

                            <dt class="col-5 text-secondary fw-normal">CPF/CNPJ</dt>
                            <dd class="col-7">{{ $tenant->documentLabel() ?? '—' }}</dd>

                            <dt class="col-5 text-secondary fw-normal">Endereço</dt>
                            <dd class="col-7">{{ $tenant->addressLine() ?? '—' }}</dd>

                            <dt class="col-5 text-secondary fw-normal">Identificador</dt>
                            <dd class="col-7 mb-0"><code>{{ $tenant->domain }}</code></dd>
                        </dl>
                    </div>
                </div>
            </div>


            {{-- USUÁRIOS --}}
            <div class="col-12 col-lg-7">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white border-0 pt-4 px-4">
                        <h5 class="fw-bold mb-0">
                            <i class="bi bi-people me-2" style="color: #ff6500;"></i>
                            Usuários ({{ $tenant->users->count() }})
                        </h5>
                    </div>
                    <div class="list-group list-group-flush px-2 pb-2">
                        @foreach ($tenant->users as $user)
                            <div class="list-group-item d-flex justify-content-between align-items-center gap-2">
                                <div class="min-w-0">
                                    <div class="fw-semibold text-truncate">{{ $user->name }}</div>
                                    <small class="text-secondary">{{ $user->email }}</small>
                                </div>
                                <div class="d-flex gap-1 flex-shrink-0">
                                    @if ($user->isSuperAdmin())
                                        <span class="badge text-bg-dark">Admin geral</span>
                                    @endif
                                    <span class="badge text-bg-light border">
                                        {{ $user->role === 'admin' ? 'Dono' : 'Usuário' }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

        </div>

    </div>

@endsection
