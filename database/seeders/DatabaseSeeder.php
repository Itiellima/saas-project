<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * php artisan migrate:fresh --seed
     */
    public function run(): void
    {
        // Dados de demonstração: nunca rodar em produção
        if (app()->isProduction()) {
            $this->command?->warn('Seeder de demonstração ignorado em produção.');

            return;
        }

        $this->call(DemoSeeder::class);
    }
}
