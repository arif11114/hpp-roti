<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stoks', function (Blueprint $table) {
            $table->id('id_stok');
            // Menghubungkan id_bahan di tabel stoks ke primary key id_bahan di tabel bahans
            $table->foreignId('id_bahan')->constrained('bahans', 'id_bahan')->onDelete('restrict');
            $table->date('tanggal_belanja');
            $table->decimal('jumlah_gram', 12, 2);
            $table->bigInteger('harga_beli');
            $table->date('tanggal_expired');
            $table->integer('batas_notif_expired')->default(14); // Default peringatan 14 hari sebelum expired
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stoks');
    }
};