<div>
    <div class="card">

        <div class="card-header">
            <i class="fa-solid fa-cart-plus me-2"></i>
            Add Items
        </div>

        <div class="card-body">

            {{-- ITEM --}}
            <div class="row align-items-center mb-3">

                <label
                    for="item"
                    class="col-12 col-md-2 col-lg-1 col-form-label fw-semibold"
                >
                    Item:
                </label>

                <div class="col-12 col-md-10 col-lg-11">

                    <div wire:ignore>
                        <select
                            wire:model="item_id"
                            id="item"
                            placeholder="Select an item"
                            class="form-select"
                        >
                            <option value="">
                                Select an item
                            </option>

                            @foreach ($items as $item)
                                <option value="{{ $item->id }}">
                                    {{ $item->name }} -
                                    {{ $item->type }} -
                                    {{ $item->sale_price }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                </div>

            </div>


            {{-- QUANTITY --}}
            <div class="row align-items-center mb-3">

                <label
                    for="quantity"
                    class="col-12 col-md-2 col-lg-1 col-form-label fw-semibold"
                >
                    Quantity:
                </label>

                <div class="col-12 col-md-10 col-lg-11">

                    <input
                        type="number"
                        class="form-control"
                        wire:model="quantity"
                        id="quantity"
                        min="1"
                    >

                </div>

            </div>


            {{-- DISCOUNT --}}
            <div class="row align-items-center mb-3">

                <label
                    for="discount"
                    class="col-12 col-md-2 col-lg-1 col-form-label fw-semibold"
                >
                    Discount:
                </label>

                <div class="col-12 col-md-10 col-lg-11">

                    <input
                        type="number"
                        class="form-control"
                        wire:model="discount"
                        id="discount"
                        min="0"
                        step="0.01"
                    >

                </div>

            </div>


            {{-- BOTÃO --}}
            <div class="d-flex justify-content-end">

                <button
                    type="button"
                    wire:click="save"
                    class="btn btn-primary"
                >
                    <i class="fa-solid fa-plus me-1"></i>
                    Add Item
                </button>

            </div>

        </div>

    </div>
</div>