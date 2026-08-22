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
                {{ strtoupper($serviceOrder->status ?? 'No status available.') }} -
                <strong>Customer: {{ $serviceOrder->customer_name }}</strong>
                <button class="btn btn-sm btn-primary float-end" onclick="window.print()">Print</button>
            </div>
            <div class="card-body">

                <div class="row mb-3 border rounded p-3 m-2">

                    <div class="col col-md-3">
                        <strong>
                            <p class="card-text">Phone: {{ $serviceOrder->customer_name }}</p>
                            <p class="card-text">Phone: {{ $serviceOrder->customer_phone }}</p>
                            <p class="card-text">Vehicle Plate: {{ $serviceOrder->vehicle_plate }}</p>

                        </strong>

                    </div>
                    <div class="col col-md-3">

                        <strong>
                            <p class="card-text">Vehicle Model: {{ $serviceOrder->vehicle_model }}</p>
                            <p class="card-text">Vehicle KM: {{ $serviceOrder->vehicle_km }}</p>
                            <p class="card-text">Vehicle Enter: {{ $serviceOrder->vehicle_enter?->format('d/m/Y H:i') }}</p>

                        </strong>

                    </div>

                    <div class="col col-md-3">

                        <strong>

                            <p class="card-text">Vehicle Leave: {{ $serviceOrder->vehicle_leave?->format('d/m/Y H:i') }}</p>
                            <p class="card-text">Status: {{ $serviceOrder->status }}</p>
                            <p class="card-text">Total: {{ $serviceOrder->total }}</p>

                        </strong>

                    </div>

                    <div class="col col-md-3">

                        <strong>

                            <p class="card-text">Description:
                                {{ $serviceOrder->description ?? 'No description available.' }}
                            </p>

                        </strong>

                    </div>
                </div>

                <livewire:service-order.item-form :serviceOrder="$serviceOrder" />

                <livewire:service-order.item-list :serviceOrder="$serviceOrder" />


                {{-- <select class="" id="item" name="item" placeholder="Selecione os serviços">
                    @foreach ($items as $item)
                        <option value="{{ $item->id }}">
                            {{ $item->name }} - {{ $item->type }} - {{ $item->description }}
                        </option>
                    @endforeach
                </select> --}}


            </div>
            <div class="card-footer">
                <a href="{{ route('service-orders.index') }}" class="btn btn-secondary">Back to List</a>

                <button class="btn btn-primary">End and Close service</button>

                <form action="{{ route('service-orders.destroy', $serviceOrder->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')

                    <button type="submit" class="btn btn-danger float-end"
                        onclick="return confirm('Are you sure you want to delete this service order?')">
                        Delete Service Order
                    </button>
                </form>
            </div>

        </div>
    </div>


    @push('scripts')
        <script>
            new TomSelect('#item', {
                create: true,
                sortField: {
                    field: 'text',
                    direction: 'asc'
                }
            });
        </script>
    @endpush

@endsection
