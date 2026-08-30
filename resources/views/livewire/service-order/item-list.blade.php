<div class="card border-0 shadow-sm mb-3">

    {{-- HEADER --}}
    <div class="card-header bg-white border-bottom py-3">

        <div class="d-flex align-items-center justify-content-between">

            <div>
                <h5 class="mb-1 fw-semibold">
                    <i class="bi bi-cart3 me-2" style="color: #ff6500;"></i>
                    Items and Services
                </h5>

                <small class="text-muted">
                    Items and services added to this service order.
                </small>
            </div>

            <span class="badge rounded-pill text-bg-light border">
                {{ $items->count() }}
                {{ $items->count() === 1 ? 'item' : 'items' }}
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
                        Quantity
                    </th>

                    <th class="text-end">
                        Unit Price
                    </th>

                    <th class="text-end">
                        Total
                    </th>

                    <th class="text-center" style="width: 100px;">
                        Actions
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
                                        {{ $item->type }}
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


                        {{-- TOTAL --}}
                        <td class="text-end fw-semibold">

                            R$
                            {{ number_format($item->total, 2, ',', '.') }}

                        </td>


                        {{-- ACTIONS --}}
                        <td class="text-center">

                            <button type="button" wire:click="deleteItemList({{ $item->id }})"
                                class="btn btn-sm btn-outline-danger" title="Remove item">
                                <i class="bi bi-trash"></i>
                            </button>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="5" class="text-center py-5">

                            <div class="text-muted">

                                <i class="bi bi-cart-x fs-1 d-block mb-2"></i>

                                <div class="fw-semibold">
                                    No items added
                                </div>

                                <small>
                                    Add products or services to this service order.
                                </small>

                            </div>

                        </td>

                    </tr>
                @endforelse

            </tbody>


            {{-- FOOTER --}}
            <tfoot>

                <tr class="table-light">

                    <td colspan="3" class="text-end fw-semibold">
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
