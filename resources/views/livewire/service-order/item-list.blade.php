<div class="row mb-3 border rounded p-3 m-2">
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

        <tfoot>
            <tr>
                <td colspan="3" class="text-end"><strong>Total:</strong></td>
                <td colspan="2"><strong> {{ number_format($total, 2, ',', '.') }}</strong></td>
            </tr>
        </tfoot>
    </table>
</div>
