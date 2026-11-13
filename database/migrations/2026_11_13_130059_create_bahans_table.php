<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bahans', function (Blueprint $table) {
            $table->id('id_bahan'); // Primary key auto-increment bernama id_bahan
            $table->string('nama_bahan', 100); // Kolom teks untuk nama bahan (maks 100 karakter)
            $table->timestamps(); // Otomatis membuat kolom created_at & updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bahans');
    }
};