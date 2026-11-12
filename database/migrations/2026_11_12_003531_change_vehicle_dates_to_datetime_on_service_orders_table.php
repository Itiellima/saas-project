<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * The form sends date and time, but the columns only kept the date.
     */
    public function up(): void
    {
        Schema::table('service_orders', function (Blueprint $table) {
            $table->dateTime('vehicle_enter')->nullable()->change();
            $table->dateTime('vehicle_leave')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('service_orders', function (Blueprint $table) {
            $table->date('vehicle_enter')->nullable()->change();
            $table->date('vehicle_leave')->nullable()->change();
        });
    }
};
