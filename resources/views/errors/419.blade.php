@extends('errors.layout')

@section('code', '419')
@section('icon', 'bi-clock-history')
@section('title', 'Sua sessão expirou')
@section('message', 'A página ficou aberta por muito tempo e, por segurança, o envio foi bloqueado. Recarregue a página e tente de novo; os dados digitados podem precisar ser preenchidos outra vez.')

@section('actions')
    <button type="button" class="btn btn-brand" onclick="history.back()">
        <i class="bi bi-arrow-clockwise me-2"></i>
        Voltar e tentar de novo
    </button>
@endsection
