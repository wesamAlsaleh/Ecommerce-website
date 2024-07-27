<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * order item is the product that is in the order
 */

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');  // order item belongs to an user (user who made the order)
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade'); // order item belongs to a product (product in the order)
            $table->integer('quantity')->default(1); // quantity of the product in the order
            $table->decimal('price'); // price of the product at the time of order
            $table->decimal('total'); // total price of the product in the order
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
