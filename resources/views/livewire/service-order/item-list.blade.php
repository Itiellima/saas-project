<div>
    {{-- Smile, breathe, and go slowly. - Thich Nhat Hanh --}}
    <table class="table">

        <thead>

            <tr>
                <th>Item</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Total</th>
                <th>Actions</th>
            </tr>

        </thead>

        <tbody>

            @foreach ($items as $item)
                <tr>

                    <td>{{ $item->name }}</td>

                    <td>{{ $item->quantity }}</td>

                    <td>{{ $item->price }}</td>

                    <td>{{ $item->total }}</td>

                    <td>

                        <button type="button" wire:click="deleteItemList({{ $item->id }})" class="btn btn-danger">
                            Delete
                        </button>
                    </td>

                </tr>
                
            @endforeach

        </tbody>

    </table>
</div>
