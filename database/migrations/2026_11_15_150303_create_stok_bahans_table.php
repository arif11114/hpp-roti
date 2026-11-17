<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stok_bahans', function (Blueprint $table) {
            $table->uuid('id_stok')->primary(); // Primary key UUID
            $table->foreignUuid('id_bahan')->constrained('bahans', 'id_bahan')->onDelete('restrict');
            $table->foreignUuid('id_supplier')->constrained('suppliers', 'id_supplier')->onDelete('restrict');
            $table->date('tanggal_transaksi'); // Otomatis terisi tanggal hari ini saat input
            $table->date('tanggal_produksi')->nullable(); // Opsional (bahan curah bisa kosong)
            $table->date('tanggal_expired')->nullable();  // Opsional untuk bahan tanpa expired
            $table->decimal('jumlah', 12, 2); // Sesuai satuan bahan (bukan dipaksa gram)
            $table->bigInteger('harga_beli');
            $table->integer('batas_notif_expired')->default(14);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stok_bahans');
    }
};