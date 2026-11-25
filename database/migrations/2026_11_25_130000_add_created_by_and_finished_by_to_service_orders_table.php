<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Quem abriu e quem finalizou cada OS. As OS antigas ficam sem essa informação.
     */
    public function up(): void
    {
        Schema::table('service_orders', function (Blueprint $table) {
            $table->foreignId('created_by')->nullable()->after('tenant_id')
                ->constrained('users')->nullOnDelete();
            $table->foreignId('finished_by')->nullable()->after('finished_at')
                ->constrained('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('service_orders', function (Blueprint $table) {
            $table->dropConstrainedForeignId('created_by');
            $table->dropConstrainedForeignId('finished_by');
        });
    }
};
