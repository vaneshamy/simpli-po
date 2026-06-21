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
            $table->uuid('id')->primary(); // Pakai UUID agar ID unik untuk Midtrans
            $table->string('customer_name');
            $table->string('whatsapp_number');
            $table->text('address');
            $table->integer('total_price');
            $table->string('status')->default('pending'); // Status awal selalu pending
            $table->string('snap_token')->nullable(); // Menyimpan token Midtrans
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
