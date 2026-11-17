<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bahans', function (Blueprint $table) {
            $table->uuid('id_bahan')->primary(); // Primary key UUID (bukan auto-increment)
            $table->foreignUuid('id_satuan')->constrained('satuans', 'id_satuan')->onDelete('restrict');
            $table->string('nama_bahan', 100);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bahans');
    }
};