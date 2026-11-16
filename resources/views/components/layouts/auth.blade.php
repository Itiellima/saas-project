@props(['title' => null])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ? $title . ' - OficinaOS' : 'OficinaOS' }}</title>

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

        .link-brand {
            color: #ff6500;
        }

        .link-brand:hover {
            color: #e05800;
        }

        .form-check-input:checked {
            background-color: #ff6500;
            border-color: #ff6500;
        }
    </style>

</head>


<body style="background-color: #f8f9fa;">

    <div class="min-vh-100 d-flex align-items-center py-5">

        <div class="container">

            <div class="row justify-content-center">

                <div class="col-12 col-md-9 col-lg-10 col-xl-9">

                    <div class="card border-0 shadow-lg overflow-hidden rounded-4">

                        <div class="row g-0">

                            {{-- LADO ESQUERDO (some no celular) --}}
                            <div class="col-lg-5 d-none d-lg-flex flex-column justify-content-between p-5 text-white"
                                style="background: linear-gradient(135deg, #ff7a00, #ff4d00);">

                                <div>

                                    <a href="{{ route('home') }}" class="text-white text-decoration-none d-block mb-5">
                                        <h2 class="fw-bold mb-1">
                                            <i class="bi bi-wrench me-2"></i>
                                            OficinaOS
                                        </h2>

                                        <small class="opacity-75">
                                            Gestão simples para a sua oficina
                                        </small>
                                    </a>

                                    <h3 class="fw-bold mb-3">
                                        Sua oficina organizada em um só lugar.
                                    </h3>

                                    <p class="opacity-75">
                                        Ordens de serviço, estoque de peças e faturamento do mês, no computador ou no
                                        celular.
                                    </p>

                                </div>

                                <div>

                                    <div class="d-flex align-items-center mb-3">
                                        <i class="bi bi-check-circle-fill me-3 fs-5"></i>
                                        <span>Ordens de serviço</span>
                                    </div>

                                    <div class="d-flex align-items-center mb-3">
                                        <i class="bi bi-check-circle-fill me-3 fs-5"></i>
                                        <span>Controle de estoque</span>
                                    </div>

                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-check-circle-fill me-3 fs-5"></i>
                                        <span>Faturamento do mês</span>
                                    </div>

                                </div>

                            </div>


                            {{-- FORMULÁRIO --}}
                            <div class="col-lg-7 bg-white">

                                <div class="p-4 p-md-5">

                                    {{-- Logo no celular, já que o lado esquerdo some --}}
                                    <a href="{{ route('home') }}"
                                        class="d-lg-none d-inline-flex align-items-center gap-2 fw-bold text-decoration-none mb-4"
                                        style="color: #ff6500;">
                                        <i class="bi bi-wrench"></i>
                                        OficinaOS
                                    </a>

                                    {{ $slot }}

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous">
    </script>

</body>

</html>
