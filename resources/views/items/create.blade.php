@extends('layouts.main')

@section('title', $item ? 'Editar Item - OficinaOS' : 'Novo Item - OficinaOS')

@section('content')

    <div class="container-fluid px-3 px-md-4 py-4">

        {{-- CABEÇALHO --}}
        <div class="mb-4">

            <div class="d-flex align-items-center gap-2 mb-2">

                <div class="rounded-3 d-flex align-items-center justify-content-center"
                    style="width: 45px; height: 45px; background-color: #fff1e6;">
                    <i class="fa-solid {{ $item ? 'bi-pencil' : 'bi-plus' }}" style="color: #ff6500;"></i>
                </div>

                <div>
                    <h3 class="fw-bold mb-0">
                        {{ $item ? 'Editar item ou serviço' : 'Novo item ou serviço' }}
                    </h3>

                    <small class="text-secondary">
                        {{ $item
                            ? 'Atualize as informações do item ou serviço.'
                            : 'Cadastre um produto ou serviço para utilizar nas ordens de serviço.' }}
                    </small>
                </div>

            </div>

        </div>


        <div class="row justify-content-center">

            <div class="col-12 col-xl-9">

                <form action="{{ $item ? route('items.update', $item->id) : route('items.store') }}" method="POST">

                    @csrf

                    @if ($item)
                        @method('PUT')
                    @endif


                    {{-- INFORMAÇÕES BÁSICAS --}}
                    <div class="card border-0 shadow-sm mb-4">

                        <div class="card-header bg-white border-0 pt-4 px-4">

                            <h5 class="fw-bold mb-1">
                                <i class="bi bi-info-circle me-2" style="color: #ff6500;"></i>
                                Informações básicas
                            </h5>

                            <small class="text-secondary">
                                Informe os dados principais do item.
                            </small>

                        </div>


                        <div class="card-body p-4">

                            {{-- NOME --}}
                            <div class="mb-4">

                                <label for="name" class="form-label fw-semibold">
                                    Nome
                                </label>

                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    id="name" name="name" value="{{ old('name', $item?->name) }}"
                                    placeholder="Ex.: Troca de óleo" required autofocus>

                                @error('name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>


                            <div class="row">

                                {{-- TIPO --}}
                                <div class="col-md-6 mb-3">

                                    <label for="type" class="form-label fw-semibold">
                                        Tipo
                                    </label>

                                    <select class="form-select @error('type') is-invalid @enderror" id="type"
                                        name="type" required>

                                        <option value="">
                                            Selecione o tipo
                                        </option>

                                        <option value="service"
                                            {{ old('type', $item?->type) == 'service' ? 'selected' : '' }}>
                                            Serviço
                                        </option>

                                        <option value="product"
                                            {{ old('type', $item?->type) == 'product' ? 'selected' : '' }}>
                                            Produto
                                        </option>

                                    </select>

                                    @error('type')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror

                                </div>


                                {{-- ESTOQUE --}}
                                <div class="col-md-6 mb-3">

                                    <label for="stock" class="form-label fw-semibold">
                                        Estoque
                                    </label>

                                    <div class="input-group">

                                        <span class="input-group-text bg-light">
                                            <i class="bi bi-box text-secondary"></i>
                                        </span>

                                        <input type="number" class="form-control @error('stock') is-invalid @enderror"
                                            id="stock" name="stock" min="0"
                                            value="{{ old('stock', $item?->stock ?? 0) }}" required>

                                    </div>

                                    @error('stock')
                                        <div class="text-danger small mt-1">
                                            {{ $message }}
                                        </div>
                                    @enderror

                                </div>

                            </div>


                            {{-- DESCRIÇÃO --}}
                            <div>

                                <label for="description" class="form-label fw-semibold">
                                    Descrição
                                </label>

                                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                                    rows="4" placeholder="Descreva o produto ou serviço...">{{ old('description', $item?->description) }}</textarea>

                                @error('description')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>

                        </div>

                    </div>


                    {{-- PREÇOS --}}
                    <div class="card border-0 shadow-sm mb-4">

                        <div class="card-header bg-white border-0 pt-4 px-4">

                            <h5 class="fw-bold mb-1">
                                <i class="fa-solid fa-dollar-sign me-2" style="color: #ff6500;"></i>
                                Valores
                            </h5>

                            <small class="text-secondary">
                                Defina o custo e o preço de venda.
                            </small>

                        </div>


                        <div class="card-body p-4">

                            <div class="row">

                                {{-- PREÇO DE CUSTO --}}
                                <div class="col-md-6 mb-3 mb-md-0">

                                    <label for="cost_price" class="form-label fw-semibold">
                                        Preço de custo
                                    </label>

                                    <div class="input-group">

                                        <span class="input-group-text bg-light">
                                            R$
                                        </span>

                                        <input type="number" step="0.01" min="0"
                                            class="form-control @error('cost_price') is-invalid @enderror" id="cost_price"
                                            name="cost_price" value="{{ old('cost_price', $item?->cost_price) }}"
                                            placeholder="0,00" required>

                                    </div>

                                    @error('cost_price')
                                        <div class="text-danger small mt-1">
                                            {{ $message }}
                                        </div>
                                    @enderror

                                </div>


                                {{-- PREÇO DE VENDA --}}
                                <div class="col-md-6">

                                    <label for="sale_price" class="form-label fw-semibold">
                                        Preço de venda
                                    </label>

                                    <div class="input-group">

                                        <span class="input-group-text text-white" style="background-color: #ff6500;">
                                            R$
                                        </span>

                                        <input type="number" step="0.01" min="0"
                                            class="form-control @error('sale_price') is-invalid @enderror" id="sale_price"
                                            name="sale_price" value="{{ old('sale_price', $item?->sale_price) }}"
                                            placeholder="0,00" required>

                                    </div>

                                    @error('sale_price')
                                        <div class="text-danger small mt-1">
                                            {{ $message }}
                                        </div>
                                    @enderror

                                </div>

                            </div>

                        </div>

                    </div>


                    {{-- AÇÕES --}}
                    <div class="d-flex flex-column flex-sm-row justify-content-end gap-2">

                        <a href="{{ route('items.index') }}" class="btn btn-outline-secondary px-4">
                            <i class="bi bi-arrow-left me-2"></i>
                            Cancelar
                        </a>

                        <button type="submit" class="btn text-white px-4 fw-semibold"
                            style="background-color: #ff6500;">
                            <i class="bi bi-check me-2"></i>

                            {{ $item ? 'Atualizar' : 'Salvar' }}

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

@endsection
