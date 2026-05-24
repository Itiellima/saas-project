@extends('layouts.main')

@section('title', 'New Vehicle - Workshop Management System')

@section('content')
    <div class="container">

        <div class=" alert alert-light">
            <h1>Create New Vehicle</h1>
            <p>Fill in the details to add a new vehicle to your inventory.</p>
        </div>

        {{-- Vehicle information --}}
        <div class="row border rounded p-3  m-2">
            <h4>Vehicle Information</h4>
            <div class="col-md-1">
                <label for="plate" class="form-label">PLATE</label>
                <input type="text" id="plate" name="plate" class="form-control" oninput="this.value = this.value.toUpperCase();" required autofocus>
            </div>

            <div class="col-md-2">
                <label for="model" class="form-label">MODEL</label>
                <input type="text" id="model" name="model" class="form-control" oninput="this.value = this.value.toUpperCase();" required>
            </div>

            <div class="col-md-2">
                <label for="brand" class="form-label">BRAND</label>
                <input type="text" id="brand" name="brand" class="form-control" oninput="this.value = this.value.toUpperCase();" required>
            </div>

            <div class="col-md-2">
                <label for="year" class="form-label">YEAR</label>
                <input type="number" id="year" name="year" class="form-control" required>
            </div>

            <div class="col-md-2">
                <label for="color" class="form-label">COLOR</label>
                <input type="text" id="color" name="color" class="form-control" oninput="this.value = this.value.toUpperCase();" required>
            </div>
        </div>

        {{-- Owner infomation --}}
        <div class="row">
            <div class="col-md-6">
                <div class="row mt-3 border rounded p-3 m-2">
                    <h4>Owner information</h4>
                    <div class="col-md-6">
                        <label for="owner_name" class="form-label">Owner name</label>
                        <input type="text" id="owner_name" name="owner_name" class="form-control" oninput="this.value = this.value.toUpperCase();" required>
                    </div>
        
                    <div class="col-md-6">
                        <label for="owner_contact" class="form-label">Owner contact</label>
                        <input type="text" id="owner_contact" name="owner_contact" class="form-control" required>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="row mt-3 border rounded p-3 m-2">
                    <h4>Vehicle pictures</h4>
                    <div class="col">
                        <label for="file" class="form-label">Select pictures</label>
                        <input type="file" id="file" name="car_picture" class="form-control" aria-label="Select pictures" multiple>
                    </div>
                </div>
            </div>
            
        </div>

        <div class="row border rounded p-3  m-2">
            <div class="col">
                <label for="exampleFormControlTextarea1">Description of service</label>
                <textarea class="form-control" id="exampleFormControlTextarea1" rows="3"></textarea>
            </div>
        </div>
        
        <div class="row mt-3 ">
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary">Save Vehicle</button>
            </div>
        </div>

        

    </div>
@endsection
