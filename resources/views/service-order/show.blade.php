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
                <strong>OS Number: {{ $serviceOrder->id }}</strong>
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
            </div>
            <div class="card-footer">
                <a href="{{ route('service-orders.index') }}" class="btn btn-secondary">Back to List</a>
            </div>
        </div>
    </div>




@endsection