@extends('layouts.main')

@section('title', 'Oficinas - OficinaOS')
@section('page_title', 'Oficinas')

@section('content')

    <div class="container-fluid px-3 px-md-4 py-4">

        {{-- CABEÇALHO --}}
        <div class="mb-4">
            <h3 class="fw-bold mb-0">
                <i class="bi bi-buildings me-2" style="color: #ff6500;"></i>
                Oficinas
            </h3>
            <small class="text-secondary">Todas as oficinas cadastradas na plataforma.</small>
        </div>


        {{-- FILTROS --}}
        <form method="GET" action="{{ route('admin.tenants.index') }}" class="row g-2 mb-4">

            <div class="col-12 col-md-6 col-lg-5">
                <div class="input-group">
                    <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                    <input type="search" name="search" value="{{ $search }}" class="form-control"
                        placeholder="Nome da oficina, dono ou e-mail">
                </div>
            </div>

            <div class="col-8 col-md-3 col-lg-2">
                <select name="status" class="form-select">
                    <option value="">Todos os status</option>
                    <option value="active" @selected($status === 'active')>Ativas</option>
                    <option value="suspended" @selected($status === 'suspended')>Suspensas</option>
                </select>
            </div>

            <div class="col-4 col-md-3 col-lg-2">
                <button type="submit" class="btn btn-secondary w-100">Filtrar</button>
            </div>

        </form>


        {{-- LISTA --}}
        <div class="card border-0 shadow-sm">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Oficina</th>
                            <th>Dono</th>
                            <th>Cadastro</th>
                            <th class="text-center">Usuários</th>
                            <th class="text-center">OS</th>
                            <th>Última OS</th>
                            <th class="pe-4">Status</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($tenants as $tenant)
                            @php($tenantStatus = $tenant->statusConfig())

                            <tr>
                                <td class="ps-4">
                                    <a href="{{ route('admin.tenants.show', $tenant) }}"
                                        class="fw-semibold text-decoration-none link-dark">
                                        {{ $tenant->name }}
                                    </a>
                                </td>
                                <td>
                                    <div>{{ $tenant->owner?->name ?? '—' }}</div>
                                    <small class="text-secondary">{{ $tenant->owner?->email }}</small>
                                </td>
                                <td class="text-nowrap">{{ $tenant->created_at->format('d/m/Y') }}</td>
                                <td class="text-center">{{ $tenant->users_count }}</td>
                                <td class="text-center">{{ $tenant->service_orders_count }}</td>
                                <td class="text-nowrap">
                                    @if ($tenant->service_orders_max_created_at)
                                        {{ \Illuminate\Support\Carbon::parse($tenant->service_orders_max_created_at)->diffForHumans() }}
                                    @else
                                        <span class="text-secondary">Nenhuma</span>
                                    @endif
                                </td>
                                <td class="pe-4">
                                    <span class="badge text-bg-{{ $tenantStatus['class'] }}">{{ $tenantStatus['label'] }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-secondary py-5">
                                    <i class="bi bi-search fs-2 d-block mb-1"></i>
                                    Nenhuma oficina encontrada
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>

            </div>

        </div>


        <div class="mt-3">
            {{ $tenants->links() }}
        </div>

    </div>

@endsection
