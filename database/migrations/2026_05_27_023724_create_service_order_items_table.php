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
        Schema::create('service_order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();

            $table->foreignId('service_order_id')->constrained()->cascadeOnDelete();

            $table->foreignId('item_id')->nullable()->constrained()->nullOnDelete();

            $table->string('name')->nullable();

            $table->enum('type', [
                'service',
                'product'
            ])->nullable();

            $table->integer('quantity')->default(1);

            $table->decimal('price', 10, 2)->default(0);

            $table->decimal('total', 10, 2)->default(0);


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_order_items');
    }
};
