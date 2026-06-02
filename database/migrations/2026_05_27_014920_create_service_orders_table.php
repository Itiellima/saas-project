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
        Schema::create('service_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();

            $table->foreignId('customer_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('vehicle_id')->nullable()->constrained()->nullOnDelete();

            $table->string('name')->nullable();

            $table->string('customer_name')->nullable();
            $table->string('customer_phone')->nullable();

            $table->string('vehicle_plate')->nullable();
            $table->string('vehicle_model')->nullable();
            $table->integer('vehicle_km')->nullable();

            $table->date('vehicle_enter')->nullable();
            $table->date('vehicle_leave')->nullable();

            $table->enum('status', [
                'open',
                'in_progress',
                'finished',
                'cancelled'
            ])->default('open');

            $table->text('description')->nullable();

            $table->decimal('total', 10, 2)->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_orders');
    }
};
