{{-- SIDEBAR DESKTOP --}}
<aside class="d-none d-md-flex flex-column justify-content-between position-sticky top-0 vh-100 p-3 bg-white border-end"
    style="width: 260px;">

    <div>

        {{-- LOGO --}}
        <h4 class="fw-bold mb-4 d-flex align-items-center gap-2">

            <span class="rounded-2 d-flex align-items-center justify-content-center"
                style="
                    width: 38px;
                    height: 38px;
                    background-color: #fff1e6;
                    color: #ff6500;
                ">
                <i class="bi bi-wrench"></i>
            </span>

            <span style="color: #ff6500;">
                OficinaOS
            </span>

        </h4>


        {{-- PRINCIPAL --}}
        <small class="text-uppercase text-secondary fw-bold px-2">
            Principal
        </small>


        <div class="list-group list-group-flush mt-2" style="font-weight: 500;">

            @include('layouts.components.items-sidebar')

        </div>


        {{-- GERENCIAMENTO --}}
        <small class="text-uppercase text-secondary fw-bold d-block mt-4 px-2">
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


    {{-- LOGOUT --}}
    <div>

        <form action="{{ route('logout') }}" method="POST">

            @csrf

            <button type="submit" class="list-group-item list-group-item-action text-danger rounded border-0 w-100">
                <i class="bi bi-box-arrow-right me-2"></i>
                Sair
            </button>

        </form>

    </div>

</aside>
