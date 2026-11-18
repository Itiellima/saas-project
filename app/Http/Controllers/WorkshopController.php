<?php

namespace App\Http\Controllers;

use App\Http\Requests\WorkshopRequest;
use Illuminate\Support\Facades\Auth;

/**
 * Dados da própria oficina (nome, documento, contato e endereço). Só o dono acessa.
 * O identificador interno (domain) não muda quando o nome muda.
 */
class WorkshopController extends Controller
{
    public function edit()
    {
        $tenant = Auth::user()->tenant;

        return view('workshop.edit', compact('tenant'));
    }

    public function update(WorkshopRequest $request)
    {
        Auth::user()->tenant->update($request->validated());

        return redirect()->route('workshop.edit')->with('success', 'Dados da oficina atualizados com sucesso.');
    }
}
