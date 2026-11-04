@extends('layouts.main')

@section('content')
    <div class="container">
        <form action="{{ route('profile.update') }}" method="POST">
            @csrf
            @method('PUT')

            <label for="name">Name:</label>
            <input type="text" name="name" id="name" value="{{ old('name', auth()->user()->name) }}">
            @error('name')
                <div class="text-danger">{{ $message }}</div>
            @enderror

            <label for="email">Email:</label>
            <input type="email" name="email" id="email" value="{{ old('email', auth()->user()->email) }}">
            @error('email')
                <div class="text-danger">{{ $message }}</div>
            @enderror

            <button type="submit">Update Profile</button>
        </form>
    </div>
@endsection
