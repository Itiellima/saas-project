<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Default Title')</title>

    {{-- Bootstrap CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>


<body style="background-color: ;">

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    {{-- content --}}
    {{-- <body>
        <div class="container-fluid">
    
            <div class="row">
    
                <div class="col-md-2 p-0">
                    @include('layouts.sidebar')
                </div>
    
                <div class="col-md-10 p-0">
    
                    @include('layouts.header')
                    @yield('content')
    
                </div>
            </div>
    
        </div>
    </body>

    @include('layouts.footer') --}}

    <body class="bg-light">

        <div class="d-flex">

            <!-- SIDEBAR DESKTOP -->
            @include('layouts.sidebar')

            <!-- MAIN -->
            <div class="flex-grow-1">

                <!-- TOPBAR -->
                @include('layouts.topbar')

                <!-- PRINCIPAL CONTENT -->
                @include('layouts.principal-content')

            </div>

        </div>

        <!-- MOBILE SIDEBAR -->
        @include('layouts.mobile-sidebar')

        {{-- Bootstrap JS --}}
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous">
        </script>

        @stack('scripts')

    </body>

</html>
