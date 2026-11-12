@extends('layouts.main')

@section('title', 'Itens e Serviços - OficinaOS')
@section('page_title', 'Itens e Serviços')

@section('content')

    <div class="container py-3">

        {{-- HEADER --}}
        <div class="alert alert-light border shadow-sm">

            <h4 class="text-black mb-2">
                <i class="bi bi-boxes-stacked me-2 text-primary"></i>
                Itens e Serviços
            </h4>

            <p class="text-muted mb-0">
                Gerencie os produtos e serviços usados nas ordens de serviço:
                cadastre, edite ou exclua.
            </p>

        </div>


        {{-- BOTÃO --}}
        <div class="mb-4">

            <a href="{{ route('items.create') }}" class="btn text-white" style="background-color: #ff6500;">
                <i class="bi bi-plus me-1"></i>
                Novo item ou serviço
            </a>

        </div>


        {{-- LISTA --}}
        <div class="row g-3">

            @foreach ($items as $item)
                <div class="col-12 col-sm-6 col-lg-4 col-xl-3">

                    <div class="card h-100 shadow-sm">

                        <div class="card-body">

                            <h5 class="card-title text-truncate" title="{{ $item->name }}">
                                {{ $item->name }}
                            </h5>

                            <p class="card-text text-muted">
                                {{ $item->description }}
                            </p>

                            <div class="small">

                                <div class="d-flex justify-content-between mb-1">
                                    <strong>Tipo:</strong>
                                    <span>
                                        {{ $item->type === 'product' ? 'Produto' : 'Serviço' }}
                                    </span>
                                </div>

                                <div class="d-flex justify-content-between mb-1">
                                    <strong>Preço de custo:</strong>
                                    <span>
                                        R$ {{ number_format($item->cost_price, 2, ',', '.') }}
                                    </span>
                                </div>

                                <div class="d-flex justify-content-between mb-1">
                                    <strong>Preço de venda:</strong>
                                    <span>
                                        R$ {{ number_format($item->sale_price, 2, ',', '.') }}
                                    </span>
                                </div>

                                <div class="d-flex justify-content-between">
                                    <strong>Estoque:</strong>
                                    <span>
                                        {{ $item->stock }}
                                    </span>
                                </div>

                            </div>

                        </div>


                        {{-- AÇÕES --}}
                        <div class="card-footer bg-white border-top-0">

                            <div class="d-flex gap-2">

                                <a href="{{ route('items.edit', $item->id) }}" class="btn btn-sm btn-secondary flex-fill">
                                    <i class="bi bi-pencil me-1"></i>
                                    Editar
                                </a>

                                <form action="{{ route('items.destroy', $item->id) }}" method="POST" class="flex-fill">

                                    @csrf
                                    @method('DELETE')

                                    <button type="submit" class="btn btn-sm btn-danger w-100"
                                        onclick="return confirm('Tem certeza que deseja excluir este item ou serviço?')">
                                        <i class="bi bi-trash me-1"></i>
                                        Excluir
                                    </button>

                                </form>

                            </div>

                        </div>

                    </div>

                </div>
            @endforeach

        </div>

    </div>

@endsection
