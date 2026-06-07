@extends('layouts.main')

@section('title', 'OS - Workshop Management System')

@section('content')

    <div class="container">
        <div class="alert alert-light">
            <h4 class="text-black">
                Add a new item or service
            </h4>
            <p class="text-muted">
                Here you can add a new item or service to your inventory, fill in the details and save it to be used in your
                service orders.
            </p>
        </div>

        <div class="container">
            <form action="{{ route('items.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="form-control" id="name" name="name" value="" required>
                </div>
                <div class="mb-3 col-md-6">
                    <label for="type" class="form-label">Type</label>
                    <select class="form-select" id="type" name="type" required>
                        <option value="">Select Type</option>
                        <option value="service">Service</option>
                        <option value="product">Product</option>
                    </select>
                </div>
                <div class="row">
                    <div class="mb-3 col-md-6">
                        <label for="cost_price" class="form-label">Cost Price</label>
                        <input type="number" step="0.01" class="form-control" id="cost_price" name="cost_price">
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="sale_price" class="form-label">Sale Price</label>
                        <input type="number" step="0.01" class="form-control" id="sale_price" name="sale_price">
                    </div>
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                </div>
                <div class="mb-3">
                    <label for="stock" class="form-label">Stock</label>
                    <input type="number" class="form-control" id="stock" name="stock">
                </div>
                <button type="submit" class="btn btn-primary">Save Item or Service</button>
            </form>
        </div>


    </div>








@endsection
