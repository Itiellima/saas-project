@extends('layouts.main')

@section('title', 'OS - Workshop Management System')

@section('content')

    <div class="container py-3">

        {{-- HEADER --}}
        <div class="alert alert-light border shadow-sm">

            <h4 class="text-black mb-2">
                <i class="bi bi-boxes-stacked me-2 text-primary"></i>
                Items and Services
            </h4>

            <p class="text-muted mb-0">
                Here you can manage your items and services, add new ones,
                edit or delete existing ones.
            </p>

        </div>


        {{-- BOTÃO --}}
        <div class="mb-4">

            <a href="{{ route('items.create') }}" class="btn text-white" style="background-color: #ff6500;">
                <i class="bi bi-plus me-1"></i>
                Add New Item or Service
            </a>

        </div>


        {{-- LISTA --}}
        <div class="row g-3">

            @foreach ($items as $item)
                <div class="col-12 col-sm-6 col-lg-4 col-xl-3">

                    <div class="card h-100 shadow-sm">

                        <div class="card-body">

                            <h5 class="card-title text-truncate" title="{{ $item->name }}">
                                {{ $item->name }}
                            </h5>

                            <p class="card-text text-muted">
                                {{ $item->description }}
                            </p>

                            <div class="small">

                                <div class="d-flex justify-content-between mb-1">
                                    <strong>Type:</strong>
                                    <span>
                                        {{ ucfirst($item->type) }}
                                    </span>
                                </div>

                                <div class="d-flex justify-content-between mb-1">
                                    <strong>Cost Price:</strong>
                                    <span>
                                        ${{ number_format($item->cost_price, 2) }}
                                    </span>
                                </div>

                                <div class="d-flex justify-content-between mb-1">
                                    <strong>Sale Price:</strong>
                                    <span>
                                        ${{ number_format($item->sale_price, 2) }}
                                    </span>
                                </div>

                                <div class="d-flex justify-content-between">
                                    <strong>Stock:</strong>
                                    <span>
                                        {{ $item->stock }}
                                    </span>
                                </div>

                            </div>

                        </div>


                        {{-- AÇÕES --}}
                        <div class="card-footer bg-white border-top-0">

                            <div class="d-flex gap-2">

                                <a href="{{ route('items.edit', $item->id) }}" class="btn btn-sm btn-secondary flex-fill">
                                    <i class="bi bi-pencil me-1"></i>
                                    Edit
                                </a>

                                <form action="{{ route('items.destroy', $item->id) }}" method="POST" class="flex-fill">

                                    @csrf
                                    @method('DELETE')

                                    <button type="submit" class="btn btn-sm btn-danger w-100"
                                        onclick="return confirm('Are you sure you want to delete this item or service?')">
                                        <i class="bi bi-trash me-1"></i>
                                        Delete
                                    </button>

                                </form>

                            </div>

                        </div>

                    </div>

                </div>
            @endforeach

        </div>

    </div>

@endsection
