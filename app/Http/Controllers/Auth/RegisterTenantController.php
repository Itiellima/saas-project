<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules\Password;

class RegisterTenantController extends Controller
{
    //

    public function create()
    {
        return view('auth.register');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tenant_name' => ['required', 'string', 'max:255'],
            'user_name' => ['required', 'string', 'max:255'],
            'user_email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        try {

            DB::beginTransaction();

            $tenant = Tenant::create([
                'name' => $validated['tenant_name'],
                'domain' => Tenant::uniqueDomain($validated['tenant_name']),
            ]);

            $user = User::create([
                'tenant_id' => $tenant->id,
                'name' => $validated['user_name'],
                'email' => $validated['user_email'],
                'password' => Hash::make($validated['password']),
            ]);

            // role fica fora do $fillable para não poder vir de um formulário
            $user->forceFill(['role' => 'admin'])->save();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Error creating tenant and user: ' . $e->getMessage());
            
            return back()->withErrors(['error' => 'Ocorreu um erro ao criar a empresa. Tente novamente.']);
        }

        Auth::login($user);

        return redirect()->route('dashboard')->with('success', 'Empresa e usuário criados com sucesso!');
    }
}
