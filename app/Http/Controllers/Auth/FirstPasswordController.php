<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

/**
 * Primeiro acesso de um funcionário: troca a senha provisória criada pelo dono.
 */
class FirstPasswordController extends Controller
{
    public function edit()
    {
        if (! Auth::user()->must_change_password) {
            return redirect()->route('dashboard');
        }

        return view('auth.first-password');
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'password' => ['required', 'string', 'confirmed', Password::defaults()],
        ]);

        if (Hash::check($request->input('password'), $user->password)) {
            return back()->withErrors(['password' => 'Escolha uma senha diferente da provisória.']);
        }

        $user->forceFill([
            'password' => $request->input('password'),
            'must_change_password' => false,
        ])->save();

        return redirect()->route('dashboard')->with('success', 'Senha definida. Bem-vindo ao OficinaOS!');
    }
}
