<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('service_orders', function (Blueprint $table) {
            $table->dateTime('finished_at')->nullable()->after('status');
            $table->dateTime('reopened_at')->nullable()->after('finished_at');
            $table->string('reopen_reason')->nullable()->after('reopened_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('service_orders', function (Blueprint $table) {
            $table->dropColumn(['finished_at', 'reopened_at', 'reopen_reason']);
        });
    }
};
