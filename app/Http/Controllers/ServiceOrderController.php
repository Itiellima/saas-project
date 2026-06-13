<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use App\Models\ServiceOrder;
use Illuminate\Support\Facades\Auth;


class ServiceOrderController extends Controller
{
    public function index()
    {
        $serviceOrders = ServiceOrder::where('tenant_id', Auth::user()->tenant_id)
            ->latest()
            ->paginate(20);


        return view('service-order.index', compact('serviceOrders'));
    }

    public function create()
    {
        return view('service-order.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_phone' => ['nullable', 'string', 'max:20'],
            'vehicle_plate' => ['nullable', 'string', 'max:20'],
            'vehicle_model' => ['nullable', 'string', 'max:50'],
            'vehicle_km' => ['nullable', 'integer'],
            'vehicle_leave' => ['nullable', 'date'],
            'description' => ['nullable', 'string'],
        ]);


        $serviceOrder = ServiceOrder::create([
            'tenant_id' => Auth::user()->tenant_id,

            'customer_name' => $validated['customer_name'],
            'customer_phone' => $validated['customer_phone'],

            'vehicle_plate' => $validated['vehicle_plate'],
            'vehicle_model' => $validated['vehicle_model'],
            'vehicle_km' => $validated['vehicle_km'],

            'vehicle_enter' => $validated['vehicle_enter'] ?? now(),
            'vehicle_leave' => $validated['vehicle_leave'],

            'description' => $validated['description'],
        ]);


        return redirect()->route('service-orders.show', $serviceOrder->id)->with('success', 'Service order created successfully.');
    }

    public function show($id)
    {
        $serviceOrder = ServiceOrder::where('tenant_id', Auth::user()->tenant_id)
            ->findOrFail($id);

        $items = Item::where('tenant_id', Auth::user()->tenant_id)->get();

        return view('service-order.show', compact('serviceOrder', 'items'));
    }
}
