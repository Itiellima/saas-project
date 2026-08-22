<div>
    {{-- Simplicity is an acquired taste. - Katharine Gerould --}}

    <div class="card">

        <div class="card-header">
            Add Items
        </div>

        <div class="card-body">

            <div class="mb-3">

                <div class="row align-items-center mb-3">
                    <label for="item_id" class="col-md-1 col-form-label">
                        Item:
                    </label>

                    <div class="col-md-11">
                        <div wire:ignore>
                            <select wire:model="item_id" id="item" placeholder="Select an item">
                                <option value="">
                                    Select an item
                                </option>

                                @foreach ($items as $item)
                                    <option value="{{ $item->id }}">
                                        {{ $item->name }} - {{ $item->type }} - {{ $item->sale_price }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                {{-- <select class="" id="item" name="item" placeholder="Selecione os serviços">
                    <option value="">
                        Select an item
                    </option>
                    @foreach ($items as $item)
                        <option value="{{ $item->id }}">
                            {{ $item->name }} - {{ $item->type }} - {{ $item->description }}
                        </option>
                    @endforeach
                </select> --}}
            </div>

            <div class="mb-3">

                <div class="row align-items-center mb-3">
                    <label for="quantity" class="col-md-1 col-form-label">
                        Quantity:
                    </label>

                    <div class="col-md-11">
                        <input type="number" class="form-control" wire:model="quantity" id="quantity">
                    </div>
                </div>

            </div>

            <div class="mb-3">

                <div class="row align-items-center mb-3">
                    <label for="discount" class="col-md-1 col-form-label">
                        Discount:
                    </label>

                    <div class="col-md-11">
                        <input type="number" class="form-control" wire:model="discount" id="discount">
                    </div>
                </div>

            </div>

            <button type="button" wire:click="save" class="btn btn-primary">

                Add Item

            </button>

        </div>

    </div>
</div>
