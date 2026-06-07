<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;

class ItemController extends Controller
{
    //
    public function index()
    {
        $items = Item::where('tenant_id', Auth::user()->tenant_id)
            ->paginate(20);

        return view('items.index', compact('items'));
    }

    public function create()
    {

        return view('items.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'in:service,product'],
            'stock' => ['nullable', 'integer'],
            'description' => ['nullable', 'string'],
            'cost_price' => ['nullable', 'numeric'],
            'sale_price' => ['nullable', 'numeric'],
            'quantity' => ['nullable', 'integer'],
        ]);

        $item = Item::create([
            'tenant_id' => Auth::user()->tenant_id,
            'name' => $validated['name'],
            'type' => $validated['type'],
            'stock' => $validated['stock'] ?? 0,
            'description' => $validated['description'] ?? null,
            'cost_price' => $validated['cost_price'] ?? 0,
            'sale_price' => $validated['sale_price'] ?? 0,
            'quantity' => $validated['quantity'] ?? 0,
        ]);

        return redirect()->route('items.index')->with('success', 'Item created successfully.');
    }

    public function edit($id){
        $item = Item::where('tenant_id', Auth::user()->tenant_id)->findOrFail($id);

        return view('items.create', compact('item'));
    }

    public function update(Request $request, $id)
    {
        $item = Item::where('tenant_id', Auth::user()->tenant_id)->findOrFail($id);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'in:service,product'],
            'stock' => ['nullable', 'integer'],
            'description' => ['nullable', 'string'],
            'cost_price' => ['nullable', 'numeric'],
            'sale_price' => ['nullable', 'numeric'],
            'quantity' => ['nullable', 'integer'],
        ]);

        $item->update([
            'name' => $validated['name'],
            'type' => $validated['type'],
            'stock' => $validated['stock'],
            'description' => $validated['description'],
            'cost_price' => $validated['cost_price'],
            'sale_price' => $validated['sale_price'],
            'quantity' => $validated['quantity'],
        ]);

        return redirect()->route('items.index')->with('success', 'Item updated successfully.');

    }
}
