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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('booking')->cascadeOnDelete();
            $table->string('method');                 // Transfer Bank, dst.
            $table->string('va_number')->nullable();  // Nomor Virtual Account
            $table->string('proof')->nullable();      // Bukti Pembayaran (path file)
            $table->decimal('amount', 12, 2);
            $table->enum('status', ['pending', 'lunas', 'gagal', 'refund'])->default('pending');
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};