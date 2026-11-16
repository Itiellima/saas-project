<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description"
        content="OficinaOS: ordens de serviço, estoque e faturamento da sua oficina mecânica em um só lugar.">

    <title>OficinaOS - Gestão simples para a sua oficina</title>

    @include('partials.favicon')

    {{-- Bootstrap CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">

    {{-- Bootstrap Icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        :root {
            --brand: #ff6500;
            --brand-dark: #e05800;
            --brand-soft: #fff1e6;
        }

        body {
            background-color: #fff;
            color: #212529;
        }

        .text-brand {
            color: var(--brand) !important;
        }

        .bg-brand-soft {
            background-color: var(--brand-soft);
        }

        .btn-brand {
            background-color: var(--brand);
            border-color: var(--brand);
            color: #fff;
        }

        .btn-brand:hover,
        .btn-brand:focus {
            background-color: var(--brand-dark);
            border-color: var(--brand-dark);
            color: #fff;
        }

        .btn-outline-brand {
            border-color: var(--brand);
            color: var(--brand);
        }

        .btn-outline-brand:hover {
            background-color: var(--brand);
            color: #fff;
        }

        .icon-box {
            width: 52px;
            height: 52px;
            border-radius: 12px;
            background-color: var(--brand-soft);
            color: var(--brand);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
            flex-shrink: 0;
        }

        .hero {
            background: linear-gradient(135deg, #fff7f0 0%, #fff 60%);
        }

        .hero-mockup {
            border-radius: 16px;
            box-shadow: 0 20px 50px rgba(255, 101, 0, 0.15);
        }

        .step-number {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: var(--brand);
            color: #fff;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .cta {
            background: linear-gradient(135deg, #ff7a00, #ff4d00);
        }
    </style>

</head>


<body>

    {{-- TOPO --}}
    <nav class="navbar bg-white border-bottom sticky-top">

        <div class="container py-1">

            <a href="{{ route('home') }}" class="navbar-brand d-flex align-items-center gap-2 fw-bold text-brand">
                <span class="icon-box" style="width: 38px; height: 38px; font-size: 1.1rem;">
                    <i class="bi bi-wrench"></i>
                </span>
                OficinaOS
            </a>

            <div class="d-flex gap-2">
                @auth
                    <a href="{{ route('dashboard') }}" class="btn btn-brand">
                        <i class="bi bi-speedometer2 me-1"></i>
                        Ir para o painel
                    </a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-outline-secondary">
                        Entrar
                    </a>
                    <a href="{{ route('register') }}" class="btn btn-brand d-none d-sm-inline-block">
                        Criar conta
                    </a>
                @endauth
            </div>

        </div>

    </nav>


    {{-- DESTAQUE --}}
    <header class="hero py-5">

        <div class="container py-lg-5">

            <div class="row align-items-center g-5">

                <div class="col-12 col-lg-6">

                    <span class="badge rounded-pill bg-brand-soft text-brand px-3 py-2 mb-3">
                        Feito para oficinas mecânicas
                    </span>

                    <h1 class="display-5 fw-bold mb-3">
                        Gestão simples para a sua <span class="text-brand">oficina</span>
                    </h1>

                    <p class="lead text-secondary mb-4">
                        Abra ordens de serviço, controle o estoque de peças e acompanhe o faturamento do mês,
                        tudo em um só lugar, no computador ou no celular.
                    </p>

                    <div class="d-flex flex-column flex-sm-row gap-2">
                        @auth
                            <a href="{{ route('dashboard') }}" class="btn btn-brand btn-lg px-4">
                                Ir para o painel
                            </a>
                        @else
                            <a href="{{ route('register') }}" class="btn btn-brand btn-lg px-4">
                                <i class="bi bi-rocket-takeoff me-2"></i>
                                Comece grátis
                            </a>
                            <a href="{{ route('login') }}" class="btn btn-outline-secondary btn-lg px-4">
                                Já tenho conta
                            </a>
                        @endauth
                    </div>

                </div>


                {{-- Prévia ilustrativa de uma OS --}}
                <div class="col-12 col-lg-6" aria-hidden="true">

                    <div class="card border-0 hero-mockup">

                        <div class="card-body p-4">

                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <small class="text-secondary d-block">Ordem de Serviço</small>
                                    <h4 class="fw-bold mb-0">#128</h4>
                                </div>
                                <span class="badge text-bg-warning">
                                    <i class="bi bi-arrow-repeat me-1"></i>
                                    Em andamento
                                </span>
                            </div>

                            <div class="row g-2 mb-3">
                                <div class="col-6">
                                    <div class="border rounded-3 p-2">
                                        <small class="text-secondary d-block">Cliente</small>
                                        <span class="fw-semibold">Ana Lima</span>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="border rounded-3 p-2">
                                        <small class="text-secondary d-block">Veículo</small>
                                        <span class="fw-semibold">HB20 · RIO4F22</span>
                                    </div>
                                </div>
                            </div>

                            <ul class="list-group list-group-flush mb-3">
                                <li class="list-group-item d-flex justify-content-between px-0">
                                    <span><i class="bi bi-tools text-brand me-2"></i>Revisão completa</span>
                                    <span>R$ 350,00</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between px-0">
                                    <span><i class="bi bi-box-seam text-brand me-2"></i>Óleo 5W30 (4x)</span>
                                    <span>R$ 180,00</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between px-0">
                                    <span><i class="bi bi-box-seam text-brand me-2"></i>Filtro de óleo</span>
                                    <span>R$ 35,00</span>
                                </li>
                            </ul>

                            <div class="d-flex justify-content-between align-items-center bg-brand-soft rounded-3 p-3">
                                <span class="fw-semibold">Total</span>
                                <span class="fs-4 fw-bold text-brand">R$ 565,00</span>
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </header>


    {{-- FUNCIONALIDADES --}}
    <section class="py-5" id="funcionalidades">

        <div class="container py-lg-4">

            <div class="text-center mb-5">
                <h2 class="fw-bold">Tudo o que a oficina precisa no dia a dia</h2>
                <p class="text-secondary mb-0">Sem planilhas, sem papel perdido, sem complicação.</p>
            </div>

            @php
                $features = [
                    [
                        'icon' => 'bi-clipboard-check',
                        'title' => 'Ordens de serviço',
                        'text' => 'Registre cliente, veículo, entrada e saída. Acompanhe cada OS de aberta a finalizada.',
                    ],
                    [
                        'icon' => 'bi-box-seam',
                        'title' => 'Controle de estoque',
                        'text' => 'As peças usadas saem do estoque automaticamente, e voltam se a OS for cancelada.',
                    ],
                    [
                        'icon' => 'bi-printer',
                        'title' => 'OS impressa',
                        'text' => 'Imprima a ordem de serviço com os dados da oficina e espaço para assinatura do cliente.',
                    ],
                    [
                        'icon' => 'bi-graph-up',
                        'title' => 'Faturamento do mês',
                        'text' => 'Veja quanto entrou no mês, o ticket médio e as OS que estão paradas há mais tempo.',
                    ],
                    [
                        'icon' => 'bi-exclamation-triangle',
                        'title' => 'Aviso de estoque baixo',
                        'text' => 'Saiba quais peças estão acabando antes de faltar no meio de um serviço.',
                    ],
                    [
                        'icon' => 'bi-phone',
                        'title' => 'Funciona no celular',
                        'text' => 'Acesse do computador do balcão ou do celular, direto no navegador. Nada para instalar.',
                    ],
                ];
            @endphp

            <div class="row g-4">
                @foreach ($features as $feature)
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="d-flex gap-3 h-100">
                            <div class="icon-box">
                                <i class="bi {{ $feature['icon'] }}"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold mb-1">{{ $feature['title'] }}</h5>
                                <p class="text-secondary mb-0">{{ $feature['text'] }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

        </div>

    </section>


    {{-- COMO FUNCIONA --}}
    <section class="py-5 bg-light">

        <div class="container py-lg-4">

            <div class="text-center mb-5">
                <h2 class="fw-bold">Comece em poucos minutos</h2>
                <p class="text-secondary mb-0">Do cadastro à primeira OS, sem treinamento.</p>
            </div>

            @php
                $steps = [
                    ['title' => 'Cadastre a sua oficina', 'text' => 'Informe o nome da empresa, seu e-mail e uma senha.'],
                    ['title' => 'Cadastre peças e serviços', 'text' => 'Monte o seu catálogo com preços e estoque.'],
                    ['title' => 'Abra a primeira OS', 'text' => 'Lance os itens, finalize e imprima para o cliente.'],
                ];
            @endphp

            <div class="row g-4 justify-content-center">
                @foreach ($steps as $index => $step)
                    <div class="col-12 col-md-4">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body p-4 d-flex gap-3">
                                <div class="step-number">{{ $index + 1 }}</div>
                                <div>
                                    <h5 class="fw-bold mb-1">{{ $step['title'] }}</h5>
                                    <p class="text-secondary mb-0">{{ $step['text'] }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

        </div>

    </section>


    {{-- CHAMADA FINAL --}}
    <section class="cta text-white py-5">

        <div class="container py-lg-4 text-center">

            <h2 class="fw-bold mb-3">Organize a sua oficina hoje</h2>

            <p class="lead opacity-75 mb-4">
                Crie a sua conta e abra a primeira ordem de serviço agora mesmo.
            </p>

            @auth
                <a href="{{ route('dashboard') }}" class="btn btn-light btn-lg px-4 fw-semibold text-brand">
                    Ir para o painel
                </a>
            @else
                <a href="{{ route('register') }}" class="btn btn-light btn-lg px-4 fw-semibold text-brand">
                    <i class="bi bi-rocket-takeoff me-2"></i>
                    Comece grátis
                </a>
            @endauth

        </div>

    </section>


    {{-- RODAPÉ --}}
    <footer class="py-4 border-top">

        <div class="container d-flex flex-column flex-sm-row justify-content-between align-items-center gap-2">

            <span class="fw-bold text-brand">
                <i class="bi bi-wrench me-1"></i>
                OficinaOS
            </span>

            <small class="text-secondary">
                © {{ now()->year }} OficinaOS. Todos os direitos reservados.
            </small>

        </div>

    </footer>

</body>

</html>
