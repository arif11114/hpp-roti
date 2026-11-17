<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('satuans', function (Blueprint $table) {
            $table->uuid('id_satuan')->primary(); // Primary key UUID (bukan auto-increment)
            $table->string('nama_satuan', 50);     // Contoh: Kilogram, Liter, Bungkus
            $table->string('singkatan', 15);       // Contoh: kg, L, bks
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('satuans');
    }
};