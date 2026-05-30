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

        <div class="row border rounded p-3 m-2">
            <h4>OS Information</h4>
            <div class="col-md-6">
                <label for="costumer_name" class="form-label">Costumer Name</label>
                <input type="text" id="costumer_name" name="costumer_name" class="form-control"
                    oninput="this.value = this.value.toUpperCase();" required autofocus>
            </div>

            <div class="col-md-3">
                <label for="costumer_phone" class="form-label">Costumer Phone</label>
                <input type="text" id="costumer_phone" name="costumer_phone" class="form-control" maxlength="15"
                    mask="(00) 00000-0000" required autofocus>
            </div>

            <div class="col-md-3">
                <label for="vehicle_plate" class="form-label">Vehicle Plate</label>
                <input type="text" id="vehicle_plate" name="vehicle_plate" class="form-control"
                    oninput="this.value = this.value.toUpperCase();" required>
            </div>

            <div class="col-md-3">
                <label for="vehicle_model" class="form-label">Vehicle Model</label>
                <input type="text" id="vehicle_model" name="vehicle_model" class="form-control"
                    oninput="this.value = this.value.toUpperCase();" required>
            </div>

            <div class="col-md-3">
                <label for="vehicle_km" class="form-label">Vehicle KM</label>
                <input type="number" id="vehicle_km" name="vehicle_km" class="form-control" required>
            </div>

            <div class="col-md-3">
                <label for="vehicle_enter" class="form-label">Vehicle Enter</label>
                <input type="datetime-local" id="vehicle_enter" name="vehicle_enter" class="form-control" required>
            </div>

            <div class="col-md-3">
                <label for="vehicle_leave" class="form-label">Vehicle Leave</label>
                <input type="datetime-local" id="vehicle_leave" name="vehicle_leave" class="form-control">
            </div>

            <div class="col-md-6">
                <label for="service_description" class="form-label">Service Description</label>
                <textarea class="form-control" id="service_description" name="service_description" rows="3"
                    oninput="this.value = this.value.toUpperCase();"></textarea>
            </div>

            <div class="col">
                <label for="total" class="form-label">Total</label>
                <input type="text" id="total" name="total" class="form-control" required>
            </div>

        </div>
        
        <div class="row border rounded p-3 m-2">

                <div class="mb-3">
                    <label for="customer_id" class="form-label">Customer</label>
                    <select class="form-select" id="customer_id" name="customer_id">
                        <option value="" selected>Select customer</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="vehicle_id" class="form-label">Vehicle</label>
                    <select class="form-select" id="vehicle_id" name="vehicle_id">
                        <option value="" selected>Select vehicle</option>
                    </select>
                </div>

        </div>

        <div class="row border rounded p-3  m-2">
            <button type="submit" class="btn btn-primary">Save and continue to include items</button>
        </div>




    </div>
@endsection
