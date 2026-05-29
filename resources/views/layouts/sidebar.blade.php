<!-- SIDEBAR DESKTOP -->
<aside class="d-none d-md-flex flex-column justify-content-between vh-100 p-3 bg-white border-end" style="width: 260px;">

    <div>

        <h4 class="text-primary mb-4">
            <i class="fa-solid fa-wrench"></i>
            OficinaOS
        </h4>

        <small class="text-uppercase text-secondary fw-bold">
            Principal
        </small>

        <div class="list-group list-group-flush mt-2">

            <a href="{{ route('dashboard') }}" class="list-group-item list-group-item-action active rounded">
                <i class="fa-solid fa-chart-pie me-2"></i>
                Dashboard
            </a>

            <a href="#" class="list-group-item list-group-item-action rounded">
                <i class="fa-solid fa-boxes-stacked me-2"></i>
                Stock
            </a>

            <a href="{{ route('vehicles.create') }}" class="list-group-item list-group-item-action rounded">
                <i class="fa-solid fa-car me-2"></i>
                Vehicles
            </a>

            <a href="#" class="list-group-item list-group-item-action rounded">
                <i class="fa-solid fa-users me-2"></i>
                Customers
            </a>

        </div>

        <small class="text-uppercase text-secondary fw-bold d-block mt-4">
            Gerenciamento
        </small>

        <div class="list-group list-group-flush mt-2">

            <a href="#" class="list-group-item list-group-item-action rounded">
                <i class="fa-solid fa-user me-2"></i>
                Profile
            </a>

            <a href="#" class="list-group-item list-group-item-action rounded">
                <i class="fa-solid fa-gear me-2"></i>
                Settings
            </a>

        </div>

    </div>

    <div>

        <form action="{{ route('logout') }}" method="POST">
            @csrf
            @method('POST')

            <button class="list-group-item list-group-item-action text-danger rounded border-0">
                <i class="fa-solid fa-right-from-bracket me-2"></i>
                Logout
            </button>
        </form>

    </div>

</aside>
