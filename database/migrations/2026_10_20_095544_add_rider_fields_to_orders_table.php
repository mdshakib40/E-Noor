
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('orders', 'rider_id')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->foreignId('rider_id')
                    ->nullable()
                    ->constrained('users')
                    ->nullOnDelete();
            });
        }

        foreach (['rider_accepted_at', 'picked_up_at', 'delivered_at'] as $column) {
            if (! Schema::hasColumn('orders', $column)) {
                Schema::table('orders', function (Blueprint $table) use ($column) {
                    $table->timestamp($column)->nullable();
                });
            }
        }
    }

    public function down(): void
    {
        // Intentionally leave columns in place to protect existing data.
    }
};
