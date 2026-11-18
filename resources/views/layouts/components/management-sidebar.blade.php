<a href="{{ route('profile.edit') }}"
    class="list-group-item list-group-item-action rounded {{ request()->routeIs('profile.*') ? 'active' : '' }}">
    <i class="bi bi-person me-2"></i>
    Perfil
</a>

{{-- Só para o administrador geral da plataforma --}}
@if (Auth::user()?->isSuperAdmin())
    <a href="{{ route('admin.dashboard') }}"
        class="list-group-item list-group-item-action rounded {{ request()->routeIs('admin.*') ? 'active' : '' }}">
        <i class="bi bi-shield-lock me-2"></i>
        Administração
    </a>
@endif
