<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('menu_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('restaurant_id')
                ->constrained('restaurants')
                ->cascadeOnDelete();
            $table->string('name');
            $table->string('slug');
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2);
            $table->string('image')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->boolean('is_available')->default(true);
            $table->timestamps();

            // Slug must be unique per restaurant, not globally.
            $table->unique(['restaurant_id', 'slug']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menu_items');
    }
};