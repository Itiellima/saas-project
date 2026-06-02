<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ServiceOrder;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {


        $tenantId = Auth::id() ? Auth::user()->tenant_id : null;

        $serviceOrders = ServiceOrder::where('tenant_id', $tenantId)->get();


        return view('dashboard', compact('serviceOrders'));
    }
}
