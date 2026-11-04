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
        Schema::create('pembayaran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('booking')->cascadeOnDelete();
            $table->string('metode');                 // Transfer Bank, dst.
            $table->string('nomor_va')->nullable();
            $table->string('bukti')->nullable();       // path file upload
            $table->decimal('jumlah', 12, 2);
            $table->enum('status', ['menunggu', 'lunas', 'gagal', 'refund'])->default('menunggu');
            $table->timestamp('dibayar_pada')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayaran');
    }
};
