@extends('layouts.main')

@section('title', 'Ordens de Serviço - OficinaOS')
@section('page_title', 'Ordens de Serviço')

@section('content')

    <div class="container-fluid">
        <livewire:service-order.index />
    </div>

@endsection
