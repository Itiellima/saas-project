<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ServiceOrder;
use App\Models\Tenant;

/**
 * Painel do administrador geral: só números e cadastros, nunca o conteúdo das OS.
 */
class DashboardController extends Controller
{
    public function index()
    {
        $month = [now()->startOfMonth(), now()->endOfMonth()];

        $stats = [
            'tenants' => Tenant::count(),
            'active' => Tenant::where('status', 'active')->count(),
            'suspended' => Tenant::where('status', 'suspended')->count(),
            'new_this_month' => Tenant::whereBetween('created_at', $month)->count(),
            'orders_this_month' => ServiceOrder::whereBetween('created_at', $month)->count(),
        ];

        $latestTenants = Tenant::with('owner')->latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'latestTenants'));
    }
}
