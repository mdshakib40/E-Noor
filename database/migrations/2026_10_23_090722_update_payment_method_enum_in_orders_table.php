
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            ALTER TABLE orders
            MODIFY payment_method
            ENUM('cash_on_delivery', 'sslcommerz')
            NOT NULL
            DEFAULT 'cash_on_delivery'
        ");
    }

    public function down(): void
    {
        DB::table('orders')
            ->where('payment_method', 'sslcommerz')
            ->update(['payment_method' => 'cash_on_delivery']);

        DB::statement("
            ALTER TABLE orders
            MODIFY payment_method
            ENUM('cash_on_delivery')
            NOT NULL
            DEFAULT 'cash_on_delivery'
        ");
    }
};
