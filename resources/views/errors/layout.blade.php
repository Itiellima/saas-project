{{--
    Layout das páginas de erro. Não consulta banco nem sessão: precisa
    funcionar mesmo quando o próprio erro é o banco fora do ar.
--}}
<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title') - OficinaOS</title>

    @include('partials.favicon')

    {{-- Bootstrap CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">

    {{-- Bootstrap Icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        .btn-brand {
            background-color: #ff6500;
            border-color: #ff6500;
            color: #fff;
        }

        .btn-brand:hover,
        .btn-brand:focus {
            background-color: #e05800;
            border-color: #e05800;
            color: #fff;
        }
    </style>

</head>


<body style="background-color: #f8f9fa;">

    <main class="min-vh-100 d-flex align-items-center py-5">

        <div class="container">

            <div class="row justify-content-center">

                <div class="col-12 col-md-8 col-lg-6">

                    <div class="card border-0 shadow-sm rounded-4 text-center p-4 p-md-5">

                        <a href="{{ url('/') }}" class="d-inline-flex align-items-center justify-content-center gap-2 fw-bold text-decoration-none mb-4"
                            style="color: #ff6500;">
                            <i class="bi bi-wrench"></i>
                            OficinaOS
                        </a>

                        <div class="mx-auto mb-4 rounded-circle d-flex align-items-center justify-content-center"
                            style="width: 88px; height: 88px; background-color: #fff1e6;">
                            <i class="bi @yield('icon') fs-1" style="color: #ff6500;"></i>
                        </div>

                        <small class="text-secondary fw-semibold">Erro @yield('code')</small>

                        <h1 class="h3 fw-bold mt-1 mb-3">@yield('title')</h1>

                        <p class="text-secondary mb-4">@yield('message')</p>

                        <div class="d-flex flex-column flex-sm-row justify-content-center gap-2">
                            @section('actions')
                                <button type="button" class="btn btn-outline-secondary" onclick="history.back()">
                                    <i class="bi bi-arrow-left me-2"></i>
                                    Voltar
                                </button>

                                <a href="{{ url('/dashboard') }}" class="btn btn-brand">
                                    <i class="bi bi-house me-2"></i>
                                    Ir para o painel
                                </a>
                            @show
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </main>

</body>

</html>
