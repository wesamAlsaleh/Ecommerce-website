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
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade'); // address belongs to an order
            $table->string('first_name')->nullable(); // first name of the user
            $table->string('last_name')->nullable(); // last name of the user
            $table->string('phone')->nullable(); // phone number of the address
            $table->string('home')->nullable();
            $table->string('street')->nullable();
            $table->string('block')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};
