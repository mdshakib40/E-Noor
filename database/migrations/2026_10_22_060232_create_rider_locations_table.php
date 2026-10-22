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
    Schema::create('rider_locations', function (Blueprint $table) {
        $table->id();

        $table->foreignId('order_id')
            ->unique()
            ->constrained('orders')
            ->cascadeOnDelete();

        $table->foreignId('rider_id')
            ->constrained('users')
            ->cascadeOnDelete();

        $table->decimal('latitude', 10, 7);
        $table->decimal('longitude', 10, 7);

        $table->decimal('heading', 6, 2)->nullable();
        $table->decimal('speed', 7, 2)->nullable();

        $table->timestamp('recorded_at')->nullable();
        $table->timestamps();

        $table->index(['rider_id', 'recorded_at']);
    });
}

public function down(): void
{
    Schema::dropIfExists('rider_locations');
}

};
