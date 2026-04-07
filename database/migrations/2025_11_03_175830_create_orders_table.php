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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            // Link to the user who placed the order
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            
            // Recipient information
            $table->string('customer_name'); // Name (even with user_id, recipient might be different)
            $table->string('shipping_phone');
            $table->text('shipping_address');
            
            $table->decimal('total_price', 15, 2); // Total price of the order
            
            // Order status: pending, processing, completed, cancelled
            $table->string('status')->default('pending'); 
            
            $table->timestamps(); // created_at, updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};