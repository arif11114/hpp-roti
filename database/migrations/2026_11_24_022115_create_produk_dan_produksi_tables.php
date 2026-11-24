<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tambah kolom sisa_stok pada stok_bahans untuk pelacakan pemotongan stok FEFO
        Schema::table('stok_bahans', function (Blueprint $table) {
            $table->decimal('sisa_stok', 12, 2)->default(0)->after('jumlah');
        });

        // Sinkronkan sisa_stok awal dengan jumlah beli yang sudah ada
        DB::table('stok_bahans')->update(['sisa_stok' => DB::raw('jumlah')]);

        // 2. Master Produk Roti
        Schema::create('produks', function (Blueprint $table) {
            $table->uuid('id_produk')->primary();
            $table->string('kode_produk', 30)->unique();
            $table->string('nama_produk', 100)->unique();
            $table->integer('porsi_per_batch')->default(1); // Jumlah roti dihasilkan per 1 resep
            $table->bigInteger('biaya_operasional')->default(0); // Gas, listrik, tenaga kerja per batch (opsional)
            $table->bigInteger('harga_jual')->default(0); // Estimasi harga jual per unit roti
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });

        // 3. Komposisi / Resep Produk (per 1 Batch Resep)
        Schema::create('resep_produks', function (Blueprint $table) {
            $table->uuid('id_resep')->primary();
            $table->foreignUuid('id_produk')->constrained('produks', 'id_produk')->onDelete('cascade');
            $table->foreignUuid('id_bahan')->constrained('bahans', 'id_bahan')->onDelete('restrict');
            $table->decimal('jumlah_takaran', 12, 2); // Takaran sesuai satuan bahan baku
            $table->timestamps();
        });

        // 4. Transaksi Produksi Roti
        Schema::create('produksis', function (Blueprint $table) {
            $table->uuid('id_produksi')->primary();
            $table->string('kode_produksi', 40)->unique();
            $table->foreignUuid('id_produk')->constrained('produks', 'id_produk')->onDelete('restrict');
            $table->foreignId('user_id')->constrained('users')->onDelete('restrict');
            $table->date('tanggal_produksi');
            $table->decimal('jumlah_batch', 8, 2); // Misal: 1 batch, 2 batch, 0.5 batch
            $table->integer('total_unit_hasil'); // jumlah_batch * porsi_per_batch
            $table->bigInteger('total_biaya_bahan')->default(0);
            $table->bigInteger('total_biaya_operasional')->default(0);
            $table->bigInteger('total_hpp')->default(0); // total_biaya_bahan + total_biaya_operasional
            $table->bigInteger('hpp_per_unit')->default(0); // total_hpp / total_unit_hasil
            $table->text('catatan')->nullable();
            $table->timestamps();
        });

        // 5. Detail Pemakaian Bahan & Pemotongan Batch Stok per Produksi
        Schema::create('detail_produksis', function (Blueprint $table) {
            $table->uuid('id_detail_produksi')->primary();
            $table->foreignUuid('id_produksi')->constrained('produksis', 'id_produksi')->onDelete('cascade');
            $table->foreignUuid('id_bahan')->constrained('bahans', 'id_bahan')->onDelete('restrict');
            $table->foreignUuid('id_stok')->constrained('stok_bahans', 'id_stok')->onDelete('restrict');
            $table->decimal('jumlah_terpakai', 12, 2);
            $table->bigInteger('harga_satuan_batch'); // Harga per unit satuan dari batch stok tersebut
            $table->bigInteger('subtotal_biaya');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_produksis');
        Schema::dropIfExists('produksis');
        Schema::dropIfExists('resep_produks');
        Schema::dropIfExists('produks');

        Schema::table('stok_bahans', function (Blueprint $table) {
            $table->dropColumn('sisa_stok');
        });
    }
};