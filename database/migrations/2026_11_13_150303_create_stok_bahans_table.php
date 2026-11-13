<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stok_bahans', function (Blueprint $table) {
            $table->id('id_stok');
            $table->foreignId('id_bahan')->constrained('bahans', 'id_bahan')->onDelete('cascade');
            $table->date('tanggal_belanja');
            $table->decimal('jumlah_gram', 10, 2);
            $table->bigInteger('harga_beli');
            $table->date('tanggal_expired');
            $table->integer('batas_notif_expired')->default(14);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stok_bahans');
    }
};