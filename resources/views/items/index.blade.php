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

                    <div class="card h-100 shadow-sm {{ $item->active ? '' : 'opacity-75' }}">

                        <div class="card-body">

                            <div class="d-flex justify-content-between align-items-start gap-2">

                                <h5 class="card-title text-truncate" title="{{ $item->name }}">
                                    {{ $item->name }}
                                </h5>

                                @unless ($item->active)
                                    <span class="badge text-bg-secondary flex-shrink-0">Inativo</span>
                                @endunless

                            </div>

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

                                @if (! $item->active)
                                    {{-- REATIVAR --}}
                                    <form action="{{ route('items.toggle', $item->id) }}" method="POST" class="flex-fill">

                                        @csrf
                                        @method('PUT')

                                        <button type="submit" class="btn btn-sm btn-outline-success w-100">
                                            <i class="bi bi-arrow-counterclockwise me-1"></i>
                                            Reativar
                                        </button>

                                    </form>
                                @elseif ($item->is_used)
                                    {{-- DESATIVAR: já usado em OS, não pode ser excluído --}}
                                    <form action="{{ route('items.toggle', $item->id) }}" method="POST" class="flex-fill">

                                        @csrf
                                        @method('PUT')

                                        <button type="submit" class="btn btn-sm btn-outline-danger w-100"
                                            title="Já usado em ordens de serviço: desative em vez de excluir"
                                            onclick="return confirm('Desativar este item? Ele não aparecerá mais ao adicionar itens nas OS, mas o histórico é mantido.')">
                                            <i class="bi bi-slash-circle me-1"></i>
                                            Desativar
                                        </button>

                                    </form>
                                @else
                                    {{-- EXCLUIR: nunca usado em OS --}}
                                    <form action="{{ route('items.destroy', $item->id) }}" method="POST" class="flex-fill">

                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" class="btn btn-sm btn-danger w-100"
                                            onclick="return confirm('Tem certeza que deseja excluir este item ou serviço?')">
                                            <i class="bi bi-trash me-1"></i>
                                            Excluir
                                        </button>

                                    </form>
                                @endif

                            </div>

                        </div>

                    </div>

                </div>
            @endforeach

        </div>

        @if ($items->isEmpty())
            <div class="text-center text-secondary py-5">
                <i class="bi bi-box-seam fs-1 d-block mb-2"></i>
                Nenhum item cadastrado ainda.
            </div>
        @endif

        <div class="mt-4">
            {{ $items->links() }}
        </div>

    </div>

@endsection
