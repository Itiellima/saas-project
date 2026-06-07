@extends('layouts.main')

@section('title', 'OS - Workshop Management System')

@section('content')

    <div class="container">
        <div class="alert alert-light">
            <h4 class="text-black">
                See about your items and services
            </h4>
            <p class="text-muted">
                Here you can manage your items and services, add new ones, edit or delete existing ones.
            </p>
        </div>

        <div class="container">
            <a href="{{ route('items.create') }}" class="btn btn-primary mb-3">
                Add New Item or Service
            </a>
        </div>

        <div class="container">
            @foreach ($items as $item)
                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="card-title">{{ $item->name }}</h5>
                        <p class="card-text">{{ $item->description }}</p>
                        <p>
                            <strong>Type:</strong> {{ ucfirst($item->type) }}<br>
                            <strong>Cost Price:</strong> ${{ number_format($item->cost_price, 2) }}<br>
                            <strong>Sale Price:</strong> ${{ number_format($item->sale_price, 2) }}<br>
                            <strong>Stock:</strong> {{ $item->stock }}
                        </p>
                    </div>

                    <div class="card-footer">
                        <a href="{{ route('items.edit', $item->id) }}" class="btn btn-sm btn-secondary">
                        Edit
                    </a>
                    {{-- <form action="{{ route('items.destroy', $item->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this item or service?')">
                            Delete
                        </button>
                    </form> --}}
                    </div>
                </div>
            @endforeach
        </div>









    @endsection
