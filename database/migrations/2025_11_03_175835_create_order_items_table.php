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
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();

            // Link to the order
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            
            // Link to the product (can be set to null if the product is deleted)
            $table->foreignId('product_id')->nullable()->constrained('products')->onDelete('set null');
            
            // Store the name and price at the time of purchase
            // in case the admin changes the original product info later
            $table->string('product_name'); 
            $table->decimal('price', 15, 2);
            $table->integer('quantity');
            
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
