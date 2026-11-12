{{-- TOPBAR --}}
<nav class="navbar bg-white border-bottom px-3 px-md-4 py-3">

    <div class="container-fluid">

        {{-- ESQUERDA --}}
        <div class="d-flex align-items-center">

            {{-- BOTÃO MOBILE --}}
            <button type="button" class="btn d-md-none me-3 text-white" style="background-color: #ff6500;"
                data-bs-toggle="offcanvas" data-bs-target="#mobileSidebar" aria-controls="mobileSidebar"
                aria-label="Abrir menu">
                <i class="bi bi-list fs-5"></i>
            </button>


            <h5 class="mb-0 fw-semibold">
                @yield('page_title', 'OficinaOS')
            </h5>

        </div>


        {{-- DIREITA --}}
        <div class="d-flex align-items-center gap-3">

            {{-- PESQUISA --}}
            <form action="{{ route('service-orders.index') }}" method="GET" class="d-none d-md-block" role="search">

                <div class="input-group">

                    <span class="input-group-text bg-light border-end-0">
                        <i class="bi bi-search text-secondary"></i>
                    </span>

                    <input type="search" name="search" class="form-control bg-light border-start-0"
                        placeholder="Buscar OS por cliente ou placa..." aria-label="Buscar ordens de serviço"
                        value="{{ request()->routeIs('service-orders.index') ? request('search') : '' }}"
                        style="width: 260px;">

                </div>

            </form>


            {{-- USUÁRIO --}}
            <a href="{{ route('profile.edit') }}" class="d-flex align-items-center gap-2 text-decoration-none text-body"
                title="Meu perfil">

                <div class="rounded-circle d-flex align-items-center justify-content-center"
                    style="
                        width: 36px;
                        height: 36px;
                        background-color: #fff1e6;
                        color: #ff6500;
                    ">
                    <i class="bi bi-person"></i>
                </div>

                <strong class="d-none d-sm-block">
                    {{ Auth::user()->name ?? 'Admin' }}
                </strong>

            </a>

        </div>

    </div>

</nav>
