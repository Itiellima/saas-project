@extends('layouts.main')

@section('title', 'OS - Workshop Management System')

@section('content')

    <div class="container">
        <div class="alert alert-light">
            <h4 class="text-black">
                Service Order Details
            </h4>
            <p class="text-black">
                View detailed information about your service order.
            </p>
        </div>
        <div class="card mb-3">
            <div class="card-header">
                <strong>OS Number: {{ $serviceOrder->id }}</strong> -
                {{ strtoupper($serviceOrder->status ?? 'No status available.') }}
                <button class="btn btn-sm btn-primary float-end" onclick="window.print()">Print</button>
            </div>
            <div class="card-body">
                <h5 class="card-title">Customer: {{ $serviceOrder->customer_name }}</h5>
                <p class="card-text">Phone: {{ $serviceOrder->customer_phone }}</p>
                <p class="card-text">Vehicle Plate: {{ $serviceOrder->vehicle_plate }}</p>
                <p class="card-text">Vehicle Model: {{ $serviceOrder->vehicle_model }}</p>
                <p class="card-text">Vehicle KM: {{ $serviceOrder->vehicle_km }}</p>
                <p class="card-text">Vehicle Enter: {{ $serviceOrder->vehicle_enter }}</p>
                <p class="card-text">Vehicle Leave: {{ $serviceOrder->vehicle_leave }}</p>
                <p class="card-text">Status: {{ $serviceOrder->status }}</p>
                <p class="card-text">Total: {{ $serviceOrder->total }}</p>
                <p class="card-text">Description: {{ $serviceOrder->description ?? 'No description available.' }}</p>

                <livewire:service-order.item-form :serviceOrder="$serviceOrder" />

                <livewire:service-order.item-list :serviceOrder="$serviceOrder" />


                {{-- <select class="" id="item" name="item[]" multiple placeholder="Selecione os serviços">
                    @foreach ($items as $item)
                        <option value="{{ $item->id }}">
                            {{ $item->name }} - {{ $item->type }} - {{ $item->description }}
                        </option>
                    @endforeach
                </select>

                <div id="items-container">

                </div>

                <button type="button" class="btn btn-secondary" id="add-item">
                    + Adicionar item
                </button> --}}


            </div>
            <div class="card-footer">
                <a href="{{ route('service-orders.index') }}" class="btn btn-secondary">Back to List</a>
                <button class="btn btn-primary">End service</button>
            </div>
        </div>
    </div>


    @push('scripts')
        <script>
            new TomSelect("#item", {
                maxItems: 100
            });
        </script>
    @endpush


    <template id="item-template">

        <div class="row border rounded p-3 mt-2 item-row">

            <div class="col-md-5">

                <label>Item</label>

                <select class="form-select" name="items[][id]">
                    <option value="">
                        Selecione
                    </option>
                    @foreach ($items as $item)
                        <option value="{{ $item->id }}">
                            {{ $item->name }}
                        </option>
                    @endforeach
                </select>
            </div>


            <div class="col-md-2">
                <label>Quantidade</label>
                <input type="number" class="form-control" name="items[][quantity]" value="1">
            </div>


            <div class="col-md-2">
                <label>Preço</label>
                <input type="number" step="0.01" class="form-control" name="items[][price]">
            </div>


            <div class="col-md-2">
                <label>Desconto</label>
                <input type="number" step="0.01" class="form-control" name="items[][discount]" value="0">
            </div>


            <div class="col-md-1 d-flex align-items-end">
                <button type="button" class="btn btn-danger remove-item">
                    X
                </button>
            </div>


        </div>

    </template>


    @push('scripts')
        <script>
            const button = document.getElementById('add-item');
            const container = document.getElementById('items-container');
            const template = document.getElementById('item-template');


            button.addEventListener('click', function() {

                const clone = template.content.cloneNode(true);

                container.appendChild(clone);

            });


            document.addEventListener('click', function(e) {

                if (e.target.classList.contains('remove-item')) {

                    e.target
                        .closest('.item-row')
                        .remove();

                }

            });
        </script>
    @endpush

@endsection
