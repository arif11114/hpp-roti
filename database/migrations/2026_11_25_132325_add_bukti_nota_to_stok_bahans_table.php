<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('stok_bahans', function (Blueprint $table) {
            $table->string('bukti_nota')->nullable()->after('harga_beli');
        });
    }

    public function down(): void
    {
        Schema::table('stok_bahans', function (Blueprint $table) {
            $table->dropColumn('bukti_nota');
        });
    }
};