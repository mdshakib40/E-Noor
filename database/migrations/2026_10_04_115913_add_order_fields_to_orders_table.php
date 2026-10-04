<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {

            $table->foreignId('customer_id')
                ->after('id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('restaurant_id')
                ->after('customer_id')
                ->constrained('restaurants')
                ->cascadeOnDelete();

            $table->string('order_number')
                ->unique()
                ->after('restaurant_id');

            $table->decimal('subtotal', 10, 2)
                ->after('order_number');

            $table->decimal('delivery_fee', 10, 2)
                ->default(0)
                ->after('subtotal');

            $table->decimal('discount', 10, 2)
                ->default(0)
                ->after('delivery_fee');

            $table->decimal('total', 10, 2)
                ->after('discount');

            $table->string('customer_name')
                ->after('total');

            $table->string('customer_phone')
                ->after('customer_name');

            $table->text('delivery_address')
                ->after('customer_phone');

            $table->enum('status', [
                'pending',
                'confirmed',
                'preparing',
                'ready',
                'out_for_delivery',
                'delivered',
                'cancelled',
            ])
                ->default('pending')
                ->after('delivery_address');

            $table->enum('payment_status', [
                'pending',
                'paid',
                'failed',
                'refunded',
            ])
                ->default('pending')
                ->after('status');

            $table->enum('payment_method', [
                'cash_on_delivery',
                'online',
            ])
                ->default('cash_on_delivery')
                ->after('payment_status');

            $table->text('notes')
                ->nullable()
                ->after('payment_method');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {

            $table->dropForeign(['customer_id']);
            $table->dropForeign(['restaurant_id']);

            $table->dropColumn([
                'customer_id',
                'restaurant_id',
                'order_number',
                'subtotal',
                'delivery_fee',
                'discount',
                'total',
                'customer_name',
                'customer_phone',
                'delivery_address',
                'status',
                'payment_status',
                'payment_method',
                'notes',
            ]);
        });
    }
};

