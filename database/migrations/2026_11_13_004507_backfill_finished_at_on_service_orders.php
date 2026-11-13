<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * OS finalizadas antes de existir finished_at não entrariam no faturamento.
     * O updated_at é a melhor estimativa de quando foram finalizadas.
     */
    public function up(): void
    {
        DB::table('service_orders')
            ->where('status', 'finished')
            ->whereNull('finished_at')
            ->update(['finished_at' => DB::raw('updated_at')]);
    }

    public function down(): void
    {
        //
    }
};
