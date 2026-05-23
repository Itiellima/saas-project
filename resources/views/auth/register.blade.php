@extends('layouts.main')

@section('title', 'Register')

@section('content')


    <div class="container mt-3">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header text-center">
                        <h3 class="card-title">Business Registration</h3>
                    </div>
                    <div class="card-body">
                        <!-- Registration form goes here -->
                        <form action="{{ route('register.store') }}" method="POST">
                            @csrf
                            @method('POST')

                            <div class="mb-3">
                                <label for="tenant_name">Business Name</label>
                                <input type="text" class="form-control" id="tenant_name" name="tenant_name" required
                                    autofocus>
                            </div>

                            <div class="mb-3">
                                <label for="user_email">User Email</label>
                                <input type="email" class="form-control" id="user_email" name="user_email" required>
                            </div>

                            <div class="mb-3">
                                <label for="user_name">User Name</label>
                                <input type="text" class="form-control" id="user_name" name="user_name" required>
                            </div>

                            <div class="mb-3">
                                <label for="password">Password</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>

                            <div class="mb-3">
                                <label for="password_confirmation">Confirm Password</label>
                                <input type="password" class="form-control" id="password_confirmation"
                                    name="password_confirmation" required>
                            </div>


                            <button type="submit" class="btn btn-primary">Register</button>

                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>








@endsection
