@extends('errors.layout')

@php
    // Mensagens próprias (ex.: "Esta OS está finalizada...") aparecem; a padrão do Laravel, em inglês, não
    $message = $exception->getMessage();

    if ($message === '' || $message === 'This action is unauthorized.' || $message === 'Forbidden') {
        $message = 'Você não tem permissão para fazer isso.';
    }
@endphp

@section('code', '403')
@section('icon', 'bi-shield-lock')
@section('title', 'Acesso não permitido')
@section('message', $message)
