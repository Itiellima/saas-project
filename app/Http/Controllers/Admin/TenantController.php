<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use Illuminate\Http\Request;

/**
 * Oficinas da plataforma, vistas pelo administrador geral.
 * Mostra cadastros e contagens; o conteúdo das OS (clientes, placas, valores) fica de fora.
 */
class TenantController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('search', ''));
        $status = $request->query('status');

        $tenants = Tenant::query()
            ->with('owner')
            ->withCount(['users', 'serviceOrders'])
            ->withMax('serviceOrders', 'created_at')
            ->when($search !== '', function ($query) use ($search) {
                // Agrupado para o orWhere não escapar dos outros filtros
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhereHas('users', fn ($users) => $users
                            ->where('email', 'like', "%{$search}%")
                            ->orWhere('name', 'like', "%{$search}%"));
                });
            })
            ->when(in_array($status, ['active', 'suspended'], true), fn ($query) => $query->where('status', $status))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.tenants.index', compact('tenants', 'search', 'status'));
    }

    public function show(Tenant $tenant)
    {
        $tenant->load(['users' => fn ($users) => $users->orderBy('created_at')]);

        $stats = [
            'orders' => $tenant->serviceOrders()->count(),
            'orders_this_month' => $tenant->serviceOrders()
                ->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])
                ->count(),
            'items' => $tenant->items()->count(),
            'last_order_at' => $tenant->serviceOrders()->max('created_at'),
        ];

        return view('admin.tenants.show', compact('tenant', 'stats'));
    }

    public function suspend(Tenant $tenant)
    {
        if (! $tenant->canBeSuspended()) {
            return back()->with('error', 'A oficina do administrador geral não pode ser suspensa.');
        }

        $tenant->update(['status' => 'suspended']);

        return back()->with('success', "Oficina {$tenant->name} suspensa. Os usuários dela não conseguem mais entrar.");
    }

    public function reactivate(Tenant $tenant)
    {
        $tenant->update(['status' => 'active']);

        return back()->with('success', "Oficina {$tenant->name} reativada.");
    }
}
