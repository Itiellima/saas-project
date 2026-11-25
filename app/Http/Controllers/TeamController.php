<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

/**
 * Equipe da oficina: o dono cadastra funcionários com uma senha provisória,
 * que eles trocam no primeiro acesso.
 */
class TeamController extends Controller
{
    public function index()
    {
        $members = User::where('tenant_id', Auth::user()->tenant_id)
            ->orderByRaw("role = 'admin' desc")
            ->orderBy('name')
            ->get();

        return view('team.index', compact('members'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
        ], [
            'email.unique' => 'Este e-mail já tem uma conta no OficinaOS (nesta ou em outra oficina).',
        ]);

        $password = $this->temporaryPassword();

        $member = User::create([
            'tenant_id' => Auth::user()->tenant_id,
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $password,
        ]);

        // role e must_change_password ficam fora do $fillable
        $member->forceFill(['role' => 'user', 'must_change_password' => true])->save();

        return redirect()->route('team.index')
            ->with('success', "{$member->name} foi adicionado à equipe.")
            ->with('temporary_password', $this->credentials($member, $password));
    }

    /**
     * Gera uma nova senha provisória (ex.: o funcionário esqueceu a senha e está sem e-mail).
     */
    public function resetPassword(int $id)
    {
        $member = $this->findEmployee($id);
        $password = $this->temporaryPassword();

        $member->forceFill([
            'password' => $password,
            'must_change_password' => true,
            'remember_token' => Str::random(60),
        ])->save();

        return redirect()->route('team.index')
            ->with('success', "Nova senha provisória gerada para {$member->name}.")
            ->with('temporary_password', $this->credentials($member, $password));
    }

    /**
     * Desativa (não entra mais, mas continua no histórico) ou reativa um funcionário.
     */
    public function toggle(int $id)
    {
        $member = $this->findEmployee($id);

        $member->forceFill(['active' => ! $member->active])->save();

        return redirect()->route('team.index')->with('success', $member->active
            ? "{$member->name} foi reativado e já pode entrar no sistema."
            : "{$member->name} foi desativado e não consegue mais entrar.");
    }

    /**
     * Funcionário da própria oficina (o dono não altera outro dono, nem a si mesmo, por aqui).
     */
    private function findEmployee(int $id): User
    {
        return User::where('tenant_id', Auth::user()->tenant_id)
            ->where('role', 'user')
            ->findOrFail($id);
    }

    /**
     * Fácil de ditar ou digitar: sem símbolos e sem caracteres parecidos (0/O, 1/l/I).
     */
    private function temporaryPassword(): string
    {
        $alphabet = 'abcdefghjkmnpqrstuvwxyzABCDEFGHJKMNPQRSTUVWXYZ23456789';

        return collect(range(1, 10))
            ->map(fn () => $alphabet[random_int(0, strlen($alphabet) - 1)])
            ->implode('');
    }

    /**
     * Mostrada uma única vez na tela da equipe, para o dono repassar ao funcionário.
     *
     * @return array{name: string, email: string, password: string}
     */
    private function credentials(User $member, string $password): array
    {
        return ['name' => $member->name, 'email' => $member->email, 'password' => $password];
    }
}
