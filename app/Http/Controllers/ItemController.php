<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;

class ItemController extends Controller
{
    public function index()
    {
        // Ativos primeiro; os desativados ficam no fim da lista
        $items = Item::where('tenant_id', Auth::user()->tenant_id)
            ->withExists('serviceOrderItems as is_used')
            ->orderByDesc('active')
            ->orderBy('name')
            ->paginate(20);

        return view('items.index', compact('items'));
    }

    public function create()
    {
        $item = null; // O mesmo formulário serve para criar e editar

        return view('items.create', compact('item'));
    }

    public function store(Request $request)
    {
        Item::create([
            ...$this->validated($request),
            'tenant_id' => Auth::user()->tenant_id,
        ]);

        return redirect()->route('items.index')->with('success', 'Item cadastrado com sucesso.');
    }

    public function edit($id)
    {
        $item = Item::where('tenant_id', Auth::user()->tenant_id)->findOrFail($id);

        return view('items.create', compact('item'));
    }

    public function update(Request $request, $id)
    {
        $item = Item::where('tenant_id', Auth::user()->tenant_id)->findOrFail($id);

        $item->update($this->validated($request));

        return redirect()->route('items.index')->with('success', 'Item atualizado com sucesso.');
    }

    public function destroy($id)
    {
        $item = Item::where('tenant_id', Auth::user()->tenant_id)->findOrFail($id);

        // Excluir apagaria o histórico de estoque do item
        if ($item->isUsed()) {
            return redirect()->route('items.index')->with('error', 'Este item já foi usado em ordens de serviço e não pode ser excluído. Desative-o em vez de excluir.');
        }

        $item->delete();

        return redirect()->route('items.index')->with('success', 'Item excluído com sucesso.');
    }

    public function toggle($id)
    {
        $item = Item::where('tenant_id', Auth::user()->tenant_id)->findOrFail($id);

        $item->update(['active' => ! $item->active]);

        return redirect()->route('items.index')->with('success', $item->active
            ? 'Item reativado. Ele volta a aparecer nas ordens de serviço.'
            : 'Item desativado. Ele não aparecerá mais ao adicionar itens nas ordens de serviço.');
    }

    /**
     * Validação usada ao criar e ao editar. Campos vazios viram 0.
     */
    private function validated(Request $request): array
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'in:service,product'],
            'stock' => ['nullable', 'integer', 'min:0'],
            'description' => ['nullable', 'string'],
            'cost_price' => ['nullable', 'numeric', 'min:0'],
            'sale_price' => ['nullable', 'numeric', 'min:0'],
            'quantity' => ['nullable', 'integer', 'min:0'],
        ]);

        return [
            ...$validated,
            'stock' => $validated['stock'] ?? 0,
            'cost_price' => $validated['cost_price'] ?? 0,
            'sale_price' => $validated['sale_price'] ?? 0,
            'quantity' => $validated['quantity'] ?? 0,
        ];
    }
}
