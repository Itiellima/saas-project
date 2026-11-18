<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * php artisan db:seed (pode repetir: recria só a oficina demo)
     */
    public function run(): void
    {
        // O DemoSeeder se recusa a rodar em produção
        $this->call(DemoSeeder::class);
    }
}
