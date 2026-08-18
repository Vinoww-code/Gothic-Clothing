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
            $table->string('order_code')->unique();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            
            // Extracted / Verified Identity data
            $table->string('nik')->nullable();
            $table->string('id_card_name')->nullable();
            $table->string('birth_date_place')->nullable();
            $table->string('gender')->nullable();
            $table->string('id_card_path');
            $table->string('selfie_path');
            
            // Delivery / Pickup Information
            $table->enum('delivery_method', ['pickup', 'delivery'])->default('pickup');
            $table->string('whatsapp')->nullable();
            $table->text('shipping_address')->nullable();
            
            // Payment & Order Status
            $table->enum('payment_method', ['qris', 'dana', 'ovo', 'cod']);
            $table->enum('payment_status', ['pending', 'paid', 'failed'])->default('pending');
            $table->enum('order_status', ['pending', 'confirmed', 'processing', 'completed', 'cancelled'])->default('pending');
            
            // Financial details
            $table->integer('price_per_day');
            $table->integer('rental_days')->default(1);
            $table->integer('total_amount');
            $table->text('notes')->nullable();
            
            $table->timestamps();
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
