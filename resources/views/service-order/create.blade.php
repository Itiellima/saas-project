@extends('layouts.main')

@section('title', 'OS - Workshop Management System')

@section('content')
    <div class="container ">
        <div class="alert alert-light">
            <h4 class="text-black">
                Create a new service order for your workshop.
            </h4>
            <p class="text-black">
                Workshop Management System.
            </p>
        </div>

        <form action="{{ route('service-orders.store') }}" method="POST">
            @csrf
            @method('POST')
            <div class="row border rounded p-3 m-2">
                <h4>OS Information</h4>
                <div class="col-md-6">
                    <label for="customer_name" class="form-label">Customer Name</label>
                    <input type="text" id="customer_name" name="customer_name" class="form-control"
                        oninput="this.value = this.value.toUpperCase();" required autofocus>
                </div>

                <div class="col-md-3">
                    <label for="customer_phone" class="form-label">Customer Phone</label>
                    <input type="text" id="customer_phone" name="customer_phone" class="form-control" maxlength="15"
                        mask="(00) 00000-0000" autofocus>
                </div>

                <div class="col-md-3">
                    <label for="vehicle_plate" class="form-label">Vehicle Plate</label>
                    <input type="text" id="vehicle_plate" name="vehicle_plate" class="form-control"
                        oninput="this.value = this.value.toUpperCase();" required>
                </div>

                <div class="col-md-3">
                    <label for="vehicle_model" class="form-label">Vehicle Model</label>
                    <input type="text" id="vehicle_model" name="vehicle_model" class="form-control"
                        oninput="this.value = this.value.toUpperCase();">
                </div>

                <div class="col-md-3">
                    <label for="vehicle_km" class="form-label">Vehicle KM</label>
                    <input type="number" id="vehicle_km" name="vehicle_km" class="form-control">
                </div>

                <div class="col-md-3">
                    <label for="vehicle_enter" class="form-label">Vehicle Enter</label>
                    <input type="datetime-local" id="vehicle_enter" name="vehicle_enter" class="form-control" value="{{ now()->format('Y-m-d\TH:i') }}">
                </div>

                <div class="col-md-3">
                    <label for="vehicle_leave" class="form-label">Vehicle Leave</label>
                    <input type="datetime-local" id="vehicle_leave" name="vehicle_leave" class="form-control">
                </div>

                <div class="col-md-12">
                    <label for="description" class="form-label">Service Description</label>
                    <textarea class="form-control" id="description" name="description" rows="3"
                        oninput="this.value = this.value.toUpperCase();"></textarea>
                </div>

            </div>

            <div class="row border rounded p-3  m-2">
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
        </form>


    </div>


@endsection
