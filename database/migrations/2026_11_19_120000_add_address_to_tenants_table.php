<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Endereço da oficina (sai no cabeçalho da OS impressa).
     */
    public function up(): void
    {
        Schema::table('tenants', function (Blueprint $table) {
            $table->string('zip_code', 8)->nullable()->after('document');
            $table->string('street')->nullable()->after('zip_code');
            $table->string('number', 20)->nullable()->after('street');
            $table->string('complement')->nullable()->after('number');
            $table->string('district')->nullable()->after('complement');
            $table->string('city')->nullable()->after('district');
            $table->char('state', 2)->nullable()->after('city');
        });

        // CPF/CNPJ passa a ser guardado só com números (a tela formata)
        DB::table('tenants')->whereNotNull('document')->orderBy('id')->each(function ($tenant) {
            DB::table('tenants')->where('id', $tenant->id)->update([
                'document' => preg_replace('/\D/', '', $tenant->document) ?: null,
            ]);
        });
    }

    public function down(): void
    {
        Schema::table('tenants', function (Blueprint $table) {
            $table->dropColumn(['zip_code', 'street', 'number', 'complement', 'district', 'city', 'state']);
        });
    }
};
