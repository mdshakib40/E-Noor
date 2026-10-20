
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            ALTER TABLE orders
            MODIFY status ENUM(
                'pending',
                'confirmed',
                'accepted',
                'rejected',
                'preparing',
                'ready',
                'ready_for_pickup',
                'picked_up',
                'out_for_delivery',
                'delivered',
                'completed',
                'cancelled'
            ) NOT NULL DEFAULT 'pending'
        ");
    }

    public function down(): void
    {
        DB::statement("
            UPDATE orders
            SET status = CASE
                WHEN status = 'accepted' THEN 'confirmed'
                WHEN status = 'rejected' THEN 'cancelled'
                WHEN status = 'ready_for_pickup' THEN 'ready'
                WHEN status = 'picked_up' THEN 'out_for_delivery'
                WHEN status = 'completed' THEN 'delivered'
                ELSE status
            END
        ");

        DB::statement("
            ALTER TABLE orders
            MODIFY status ENUM(
                'pending',
                'confirmed',
                'preparing',
                'ready',
                'out_for_delivery',
                'delivered',
                'cancelled'
            ) NOT NULL DEFAULT 'pending'
        ");
    }
};
