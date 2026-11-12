<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * service_orders.total was never updated when items were added or removed.
     * Fill it from the items already saved.
     */
    public function up(): void
    {
        DB::statement('
            UPDATE service_orders
            SET total = (
                SELECT COALESCE(SUM(service_order_items.total), 0)
                FROM service_order_items
                WHERE service_order_items.service_order_id = service_orders.id
            )
        ');
    }

    public function down(): void
    {
        //
    }
};
