<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\ServiceOrder;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    // Produtos com estoque igual ou abaixo disto aparecem como "estoque baixo"
    public const LOW_STOCK_LIMIT = 5;

    public function index()
    {
        $tenantId = Auth::user()->tenant_id;

        $orders = fn () => ServiceOrder::where('tenant_id', $tenantId);

        // OS finalizadas no mês atual (canceladas e reabertas ficam de fora)
        $finishedThisMonth = $orders()
            ->where('status', 'finished')
            ->whereBetween('finished_at', [now()->startOfMonth(), now()->endOfMonth()]);

        $openCount = $orders()->where('status', 'open')->count();
        $inProgressCount = $orders()->where('status', 'in_progress')->count();
        $finishedCount = (clone $finishedThisMonth)->count();
        $revenue = (float) (clone $finishedThisMonth)->sum('total');

        $stats = [
            'open' => $openCount,
            'in_progress' => $inProgressCount,
            'pending' => $openCount + $inProgressCount,
            'finished' => $finishedCount,
            'revenue' => $revenue,
            'average_ticket' => $finishedCount > 0 ? $revenue / $finishedCount : 0,
        ];

        $oldestPending = $orders()
            ->whereIn('status', ['open', 'in_progress'])
            ->orderBy('vehicle_enter')
            ->take(5)
            ->get();

        $lowStockItems = Item::where('tenant_id', $tenantId)
            ->where('type', 'product')
            ->active()
            ->where('stock', '<=', self::LOW_STOCK_LIMIT)
            ->orderBy('stock')
            ->orderBy('name')
            ->take(5)
            ->get();

        $latestOrders = $orders()->latest()->take(5)->get();

        return view('dashboard', compact('stats', 'oldestPending', 'lowStockItems', 'latestOrders'));
    }
}
