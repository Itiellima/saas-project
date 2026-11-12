<div>

    <div class="card border-0 shadow-sm">

        {{-- HEADER --}}
        <div class="card-header bg-white border-bottom py-3">

            <div class="d-flex align-items-center gap-2">

                <div class="rounded-2 d-flex align-items-center justify-content-center"
                    style="
                        width: 38px;
                        height: 38px;
                        background-color: #fff1e6;
                        color: #ff6500;
                    ">
                    <i class="bi bi-cart-plus"></i>
                </div>

                <div>

                    <h5 class="mb-0 fw-semibold">
                        Adicionar itens
                    </h5>

                    <small class="text-muted">
                        Adicione produtos ou serviços a esta ordem de serviço.
                    </small>

                </div>

            </div>

        </div>


        {{-- BODY --}}
        <div class="card-body p-4">

            {{-- ITEM --}}
            <div class="row align-items-center mb-4">

                <label for="item" class="col-12 col-md-2 col-lg-1 col-form-label fw-semibold">
                    Item
                </label>

                <div class="col-12 col-md-10 col-lg-11">

                    <div wire:ignore.self>

                        <select wire:model="item_id" id="item" placeholder="Selecione um item" class="form-select">

                            <option value="">
                                Selecione um item
                            </option>

                            @foreach ($items as $item)
                                <option value="{{ $item->id }}">

                                    {{ $item->name }}
                                    —
                                    {{ $item->type === 'product' ? 'Produto' : 'Serviço' }}
                                    —
                                    R$ {{ number_format($item->sale_price, 2, ',', '.') }}
                                    —
                                    {{ $item->type === 'product' ? $item->stock . ' em estoque' : 'serviço' }}

                                </option>
                            @endforeach

                        </select>

                    </div>

                    @error('item_id')
                        <div class="text-danger small mt-1">
                            {{ $message }}
                        </div>
                    @enderror

                </div>

            </div>


            {{-- QUANTITY --}}
            <div class="row align-items-center mb-4">

                <label for="quantity" class="col-12 col-md-2 col-lg-1 col-form-label fw-semibold">
                    Quantidade
                </label>

                <div class="col-12 col-md-10 col-lg-11">

                    <div class="input-group">

                        <span class="input-group-text bg-light">
                            <i class="bi bi-hash"></i>
                        </span>

                        <input type="number" class="form-control" wire:model="quantity" id="quantity" min="1">

                    </div>

                    @error('quantity')
                        <div class="text-danger small mt-1">
                            {{ $message }}
                        </div>
                    @enderror

                </div>

            </div>


            {{-- DISCOUNT --}}
            <div class="row align-items-center mb-4">

                <label for="discount" class="col-12 col-md-2 col-lg-1 col-form-label fw-semibold">
                    Desconto
                </label>

                <div class="col-12 col-md-10 col-lg-11">

                    <div class="input-group">

                        <span class="input-group-text bg-light">
                            R$
                        </span>

                        <input type="number" class="form-control" wire:model="discount" id="discount" min="0"
                            step="0.01" placeholder="0.00">

                    </div>

                    @error('discount')
                        <div class="text-danger small mt-1">
                            {{ $message }}
                        </div>
                    @enderror

                </div>

            </div>


            {{-- ACTION --}}
            <div class="d-flex justify-content-end pt-2">

                <button type="button" wire:click="save" class="btn text-white px-4" style="background-color: #ff6500;">

                    <i class="bi bi-plus-lg me-1"></i>

                    Adicionar item

                </button>

            </div>

        </div>

    </div>

    @push('scripts')
        <script>
            document.addEventListener('livewire:init', () => {

                function initItemSelect() {

                    const select = document.querySelector('#item');

                    if (!select) {
                        return;
                    }

                    if (select.tomselect) {
                        select.tomselect.destroy();
                    }

                    new TomSelect(select, {
                        create: true,
                        sortField: {
                            field: 'text',
                            direction: 'asc'
                        }
                    });

                }


                initItemSelect();


                Livewire.on('item-added', () => {

                    setTimeout(() => {
                        initItemSelect();
                    }, 50);

                });

            });
        </script>
    @endpush


</div>
