{{-- MOBILE SIDEBAR --}}
<div class="offcanvas offcanvas-start" tabindex="-1" id="mobileSidebar" aria-labelledby="mobileSidebarLabel"
    style="width: 280px;">

    {{-- HEADER --}}
    <div class="offcanvas-header border-bottom">

        <h5 class="offcanvas-title fw-bold d-flex align-items-center gap-2" id="mobileSidebarLabel">
            <span class="rounded-2 d-flex align-items-center justify-content-center"
                style="
                    width: 35px;
                    height: 35px;
                    background-color: #fff1e6;
                    color: #ff6500;
                ">
                <i class="bi bi-wrench"></i>
            </span>

            OficinaOS
        </h5>


        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Fechar menu"></button>

    </div>


    {{-- BODY --}}
    <div class="offcanvas-body p-3">

        <small class="text-uppercase text-secondary fw-bold px-2">
            Principal
        </small>

        <div class="list-group list-group-flush mt-2">

            @include('layouts.components.items-sidebar')

        </div>


        <small class="text-uppercase text-secondary fw-bold px-2 d-block mt-4">
            Gerenciamento
        </small>

        <div class="list-group list-group-flush mt-2">

            <a href="#" class="list-group-item list-group-item-action rounded">
                <i class="bi bi-person me-2"></i>
                Perfil
            </a>

            <a href="#" class="list-group-item list-group-item-action rounded">
                <i class="bi bi-gear me-2"></i>
                Configurações
            </a>

        </div>

    </div>


    {{-- FOOTER --}}
    <div class="border-top p-3">

        <form action="{{ route('logout') }}" method="POST">

            @csrf

            <button type="submit" class="btn btn-outline-danger w-100">
                <i class="bi bi-box-arrow-right me-2"></i>
                Sair
            </button>

        </form>

    </div>

</div>
