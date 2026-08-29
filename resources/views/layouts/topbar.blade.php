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
                Dashboard
            </h5>

        </div>


        {{-- DIREITA --}}
        <div class="d-flex align-items-center gap-3">

            {{-- PESQUISA --}}
            <div class="d-none d-md-block">

                <div class="input-group">

                    <span class="input-group-text bg-light border-end-0">
                        <i class="bi bi-search text-secondary"></i>
                    </span>

                    <input type="text" class="form-control bg-light border-start-0" placeholder="Buscar..."
                        style="width: 220px;">

                </div>

            </div>


            {{-- USUÁRIO --}}
            <div class="d-flex align-items-center gap-2">

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

            </div>

        </div>

    </div>

</nav>
