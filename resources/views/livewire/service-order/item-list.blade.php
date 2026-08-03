<div>
    {{-- Smile, breathe, and go slowly. - Thich Nhat Hanh --}}
    <table class="table">

        <thead>

            <tr>
                <th>Item</th>
                <th>Qtd</th>
                <th>Preço</th>
                <th>Total</th>
            </tr>

        </thead>

        <tbody>

            @foreach ($items as $item)
                <tr>

                    <td>{{ $item->name }}</td>

                    <td>{{ $item->quantity }}</td>

                    <td>{{ $item->price }}</td>

                    <td>{{ $item->total }}</td>

                </tr>
            @endforeach

        </tbody>

    </table>
</div>
