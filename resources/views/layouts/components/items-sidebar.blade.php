<a href="{{ route('dashboard') }}"
    class="list-group-item list-group-item-action rounded {{ request()->routeIs('dashboard') ? 'active' : '' }}">
    <i class="bi bi-house me-2"></i>
    Dashboard
</a>

<a href="{{ route('service-orders.index') }}"
    class="list-group-item list-group-item-action rounded {{ request()->routeIs('service-orders.*') ? 'active' : '' }}">
    <i class="bi bi-clipboard me-2"></i>
    Ordens de Serviço
</a>

<a href="{{ route('items.index') }}"
    class="list-group-item list-group-item-action rounded {{ request()->routeIs('items.*') ? 'active' : '' }}">
    <i class="bi bi-box-seam me-2"></i>
    Itens e Serviços
</a>
