@extends('errors.layout')

@section('code', '503')
@section('icon', 'bi-tools')
@section('title', 'Estamos em manutenção')
@section('message', 'O OficinaOS está passando por uma atualização rápida. Volte em alguns minutos.')

@section('actions')
    <button type="button" class="btn btn-brand" onclick="location.reload()">
        <i class="bi bi-arrow-clockwise me-2"></i>
        Tentar novamente
    </button>
@endsection
