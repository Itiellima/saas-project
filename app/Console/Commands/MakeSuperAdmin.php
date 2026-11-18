<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

/**
 * Dá (ou tira, com --remove) o acesso de administrador geral a um usuário que já existe.
 * A conta é criada antes pelo cadastro normal, então a senha nunca passa pelo terminal.
 * php artisan app:make-super-admin seu@email.com dá o acesso.
 * php artisan app:make-super-admin seu@email.com --remove tira o acesso.
 */
class MakeSuperAdmin extends Command
{
    protected $signature = 'app:make-super-admin
                            {email : E-mail de um usuário já cadastrado}
                            {--remove : Tira o acesso de administrador geral}';

    protected $description = 'Dá ou tira o acesso de administrador geral de um usuário';

    public function handle(): int
    {
        $user = User::where('email', $this->argument('email'))->first();

        if (! $user) {
            $this->error('Nenhum usuário com esse e-mail. Crie a conta pelo cadastro do site primeiro.');

            return self::FAILURE;
        }

        $grant = ! $this->option('remove');

        // forceFill: is_super_admin fica fora do $fillable de propósito
        $user->forceFill(['is_super_admin' => $grant])->save();

        $this->info($grant
            ? "{$user->name} ({$user->email}) agora é administrador geral."
            : "{$user->name} ({$user->email}) não é mais administrador geral.");

        return self::SUCCESS;
    }
}
