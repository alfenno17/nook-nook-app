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
        Schema::create('ruangan', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('sayap')->nullable();       // nama sayap/gedung, dipakai di bookingsayap.blade.php
            $table->text('deskripsi')->nullable();
            $table->unsignedInteger('kapasitas')->nullable();
            $table->decimal('harga', 10, 2)->default(0);
            $table->string('gambar')->nullable();       // path/nama file gambar ruangan
            $table->enum('status', ['tersedia', 'tidak_tersedia'])->default('tersedia');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ruangan');
    }
};
