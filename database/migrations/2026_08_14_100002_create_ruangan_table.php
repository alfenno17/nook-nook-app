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
            $table->foreignId('kategori_id')->constrained('kategori')->cascadeOnDelete();
            $table->string('nama');                            // Meeting Room A, dst.
            $table->string('gedung')->nullable();
            $table->string('lantai')->nullable();
            $table->unsignedInteger('kapasitas')->nullable();  // kapasitas orang
            $table->decimal('harga_per_jam', 12, 2);
            $table->text('deskripsi')->nullable();
            $table->string('foto')->nullable();
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
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
