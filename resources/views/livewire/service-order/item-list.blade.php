<div class="card border-0 shadow-sm mb-3">

    {{-- HEADER --}}
    <div class="card-header bg-white border-bottom py-3">

        <div class="d-flex align-items-center justify-content-between">

            <div>
                <h5 class="mb-1 fw-semibold">
                    <i class="bi bi-cart3 me-2" style="color: #ff6500;"></i>
                    Itens e serviços
                </h5>

                <small class="text-muted">
                    Itens e serviços adicionados a esta ordem de serviço.
                </small>
            </div>

            <span class="badge rounded-pill text-bg-light border">
                {{ $items->count() }}
                {{ $items->count() === 1 ? 'item' : 'itens' }}
            </span>

        </div>

    </div>


    {{-- TABLE --}}
    <div class="table-responsive">

        <table class="table table-hover align-middle mb-0">

            <thead class="table-light">

                <tr>

                    <th class="ps-4">
                        Item
                    </th>

                    <th class="text-center">
                        Quantidade
                    </th>

                    <th class="text-end">
                        Preço unitário
                    </th>

                    <th class="text-end">
                        Desconto
                    </th>

                    <th class="text-end">
                        Total
                    </th>

                    <th class="text-center" style="width: 100px;">
                        Ações
                    </th>

                </tr>

            </thead>


            <tbody>

                @forelse ($items as $item)
                    <tr>

                        {{-- ITEM --}}
                        <td class="ps-4">

                            <div class="d-flex align-items-center gap-2">

                                <div class="rounded-2 d-flex align-items-center justify-content-center"
                                    style="
                                        width: 38px;
                                        height: 38px;
                                        background-color: #fff1e6;
                                        color: #ff6500;
                                    ">

                                    @if ($item->type === 'service')
                                        <i class="bi bi-tools"></i>
                                    @else
                                        <i class="bi bi-box-seam"></i>
                                    @endif

                                </div>


                                <div>

                                    <div class="fw-semibold">
                                        {{ $item->name }}
                                    </div>

                                    <small class="text-muted text-capitalize">
                                        {{ $item->type === 'product' ? 'Produto' : 'Serviço' }}
                                    </small>

                                </div>

                            </div>

                        </td>


                        {{-- QUANTITY --}}
                        <td class="text-center">

                            <span class="badge text-bg-light border">
                                {{ $item->quantity }}
                            </span>

                        </td>


                        {{-- PRICE --}}
                        <td class="text-end">

                            R$
                            {{ number_format($item->price, 2, ',', '.') }}

                        </td>


                        {{-- DISCOUNT --}}
                        <td class="text-end text-danger">

                            @if ($item->discount > 0)
                                - R$
                                {{ number_format($item->discount, 2, ',', '.') }}
                            @else
                                <span class="text-muted">—</span>
                            @endif

                        </td>


                        {{-- TOTAL --}}
                        <td class="text-end fw-semibold">

                            R$
                            {{ number_format($item->total, 2, ',', '.') }}

                        </td>


                        {{-- ACTIONS --}}
                        <td class="text-center">

                            <button type="button" wire:click="deleteItemList({{ $item->id }})"
                                class="btn btn-sm btn-outline-danger" title="Remover item">
                                <i class="bi bi-trash"></i>
                            </button>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="6" class="text-center py-5">

                            <div class="text-muted">

                                <i class="bi bi-cart-x fs-1 d-block mb-2"></i>

                                <div class="fw-semibold">
                                    Nenhum item adicionado
                                </div>

                                <small>
                                    Adicione produtos ou serviços a esta ordem de serviço.
                                </small>

                            </div>

                        </td>

                    </tr>
                @endforelse

            </tbody>


            {{-- FOOTER --}}
            <tfoot>

                <tr class="table-light">

                    <td colspan="4" class="text-end fw-semibold">
                        Total:
                    </td>

                    <td class="text-end fw-bold fs-5" style="color: #ff6500;">

                        R$
                        {{ number_format($total, 2, ',', '.') }}

                    </td>

                    <td></td>

                </tr>

            </tfoot>

        </table>

    </div>

</div>
