<?php

namespace App\Http\Controllers;

use App\Http\Requests\ServiceOrderRequest;
use App\Models\Item;
use Illuminate\Http\Request;
use App\Models\ServiceOrder;
use App\Models\StockMovement;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


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

        // Excluir apagaria os itens sem devolver o estoque e sem deixar registro
        if ($serviceOrder->items()->exists()) {
            return redirect()->route('service-orders.show', $serviceOrder->id)->with('error', 'Esta OS já tem itens e não pode ser excluída. Use "Cancelar OS".');
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
            // Com itens, o trabalho já tinha começado
            'status' => $serviceOrder->items()->exists() ? 'in_progress' : 'open',
            'finished_at' => null,
            'reopened_at' => now(),
            'reopen_reason' => $validated['reopen_reason'],
        ]);

        return redirect()->route('service-orders.show', $serviceOrder->id)->with('success', 'OS reaberta. A reabertura foi registrada.');
    }

    public function cancel(Request $request, $id)
    {
        $serviceOrder = ServiceOrder::where('tenant_id', Auth::user()->tenant_id)->findOrFail($id);

        if ($serviceOrder->isLocked()) {
            return redirect()->route('service-orders.show', $serviceOrder->id)->with('error', 'Apenas OS abertas ou em andamento podem ser canceladas.');
        }

        $validated = $request->validate([
            'cancel_reason' => ['required', 'string', 'min:10', 'max:255'],
            'cancel_confirm' => ['accepted'],
        ], [
            'cancel_reason.required' => 'Informe o motivo do cancelamento.',
            'cancel_reason.min' => 'O motivo deve ter pelo menos 10 caracteres.',
            'cancel_confirm.accepted' => 'Confirme que entende que o cancelamento não pode ser desfeito.',
        ]);

        DB::transaction(function () use ($serviceOrder, $validated) {

            // Devolve ao estoque os produtos usados na OS
            foreach ($serviceOrder->items()->where('type', 'product')->get() as $serviceOrderItem) {

                $item = Item::where('tenant_id', $serviceOrder->tenant_id)->find($serviceOrderItem->item_id);

                // O produto pode ter sido excluído do cadastro
                if (! $item) {
                    continue;
                }

                StockMovement::create([
                    'tenant_id' => $serviceOrder->tenant_id,
                    'item_id' => $item->id,
                    'service_order_id' => $serviceOrder->id,
                    'quantity' => $serviceOrderItem->quantity,
                    'type' => 'in',
                    'reason' => 'Cancelamento da ordem de serviço #' . $serviceOrder->id,
                ]);

                $item->increment('stock', $serviceOrderItem->quantity);
            }

            $serviceOrder->update([
                'status' => 'cancelled',
                'cancelled_at' => now(),
                'cancel_reason' => $validated['cancel_reason'],
            ]);
        });

        return redirect()->route('service-orders.show', $serviceOrder->id)->with('success', 'OS cancelada. Os produtos foram devolvidos ao estoque.');
    }
}
