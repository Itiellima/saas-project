<?php

namespace App\Http\Controllers;

use App\Http\Requests\ServiceOrderRequest;
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
        $serviceOrder = null; // O mesmo formulário serve para criar e editar

        return view('service-order.create', compact('serviceOrder'));
    }

    public function store(ServiceOrderRequest $request)
    {
        $validated = $request->validated();

        $serviceOrder = ServiceOrder::create([
            ...$validated,
            'tenant_id' => Auth::user()->tenant_id,
            'vehicle_enter' => $validated['vehicle_enter'] ?? now(),
        ]);


        return redirect()->route('service-orders.show', $serviceOrder->id)->with('success', 'Ordem de serviço criada com sucesso.');
    }

    public function edit($id)
    {
        $serviceOrder = ServiceOrder::where('tenant_id', Auth::user()->tenant_id)->findOrFail($id);

        if ($serviceOrder->isLocked()) {
            return redirect()->route('service-orders.show', $serviceOrder->id)->with('error', 'Esta OS está finalizada ou cancelada e não pode ser editada. Reabra a OS para corrigir.');
        }

        return view('service-order.create', compact('serviceOrder'));
    }

    public function update(ServiceOrderRequest $request, $id)
    {
        $serviceOrder = ServiceOrder::where('tenant_id', Auth::user()->tenant_id)->findOrFail($id);

        if ($serviceOrder->isLocked()) {
            return redirect()->route('service-orders.show', $serviceOrder->id)->with('error', 'Esta OS está finalizada ou cancelada e não pode ser editada. Reabra a OS para corrigir.');
        }

        $validated = $request->validated();

        $serviceOrder->update([
            ...$validated,
            'vehicle_enter' => $validated['vehicle_enter'] ?? $serviceOrder->vehicle_enter,
        ]);

        return redirect()->route('service-orders.show', $serviceOrder->id)->with('success', 'Ordem de serviço atualizada com sucesso.');
    }

    public function show($id)
    {
        $serviceOrder = ServiceOrder::where('tenant_id', Auth::user()->tenant_id)
            ->findOrFail($id);

        $items = Item::where('tenant_id', Auth::user()->tenant_id)->get();

        return view('service-order.show', compact('serviceOrder', 'items'));
    }

    public function destroy($id)
    {
        $serviceOrder = ServiceOrder::where('tenant_id', Auth::user()->tenant_id)->findOrFail($id);

        if ($serviceOrder->isLocked()) {
            return redirect()->route('service-orders.show', $serviceOrder->id)->with('error', 'Esta OS está finalizada ou cancelada e não pode ser excluída.');
        }

        $serviceOrder->delete();

        return redirect()->route('service-orders.index')->with('success', 'Ordem de serviço excluída com sucesso.');
    }

    public function closeServiceOrder($id)
    {
        $serviceOrder = ServiceOrder::where('tenant_id', Auth::user()->tenant_id)->findOrFail($id);

        if ($serviceOrder->isLocked()) {
            return redirect()->route('service-orders.show', $serviceOrder->id)->with('error', 'Esta OS já está finalizada ou cancelada.');
        }

        $serviceOrder->update([
            'status' => 'finished',
            'finished_at' => now(),
            // O carro sai quando a OS é finalizada, se a saída não foi informada antes
            'vehicle_leave' => $serviceOrder->vehicle_leave ?? now(),
        ]);

        return redirect()->route('service-orders.show', $serviceOrder->id)->with('success', 'Ordem de serviço finalizada com sucesso.');
    }

    public function reopen(Request $request, $id)
    {
        $serviceOrder = ServiceOrder::where('tenant_id', Auth::user()->tenant_id)->findOrFail($id);

        if ($serviceOrder->status !== 'finished') {
            return redirect()->route('service-orders.show', $serviceOrder->id)->with('error', 'Apenas OS finalizadas podem ser reabertas.');
        }

        $validated = $request->validate([
            'reopen_reason' => ['required', 'string', 'min:10', 'max:255'],
            'reopen_confirm' => ['accepted'],
        ], [
            'reopen_reason.required' => 'Informe o motivo da reabertura.',
            'reopen_reason.min' => 'O motivo deve ter pelo menos 10 caracteres.',
            'reopen_confirm.accepted' => 'Confirme que entende as consequências de reabrir a OS.',
        ]);

        $serviceOrder->update([
            'status' => 'open',
            'finished_at' => null,
            'reopened_at' => now(),
            'reopen_reason' => $validated['reopen_reason'],
        ]);

        return redirect()->route('service-orders.show', $serviceOrder->id)->with('success', 'OS reaberta. A reabertura foi registrada.');
    }
}
