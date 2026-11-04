<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('favorit', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('ruangan_id')->constrained('ruangan')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['user_id', 'ruangan_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('favorit');
    }
};