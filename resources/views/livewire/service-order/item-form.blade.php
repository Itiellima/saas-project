<div>
    {{-- Simplicity is an acquired taste. - Katharine Gerould --}}

    <div class="card">

        <div class="card-header">
            Add Item
        </div>

        <div class="card-body">

            <div class="mb-3">

                <label>Item</label>

                <select wire:model="item_id" class="form-select">

                    <option value="">
                        Select an item
                    </option>

                    @foreach ($items as $item)
                        <option value="{{ $item->id }}">
                            {{ $item->name }}
                        </option>
                    @endforeach

                </select>

            </div>

            <div class="mb-3">

                <label>Quantity</label>

                <input type="number" class="form-control" wire:model="quantity">

            </div>

            <div class="mb-3">

                <label>Discount</label>

                <input type="number" class="form-control" wire:model="discount">

            </div>

            <button type="button" wire:click="save" class="btn btn-primary">

                Add Item
            </button>

        </div>

    </div>
</div>
