<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('barang_masuk', function (Blueprint $table) {
            $table->string('foto_bukti')->nullable()->after('keterangan');
        });

        Schema::table('barang_keluar', function (Blueprint $table) {
            $table->string('foto_bukti')->nullable()->after('keterangan');
        });
    }

    public function down(): void
    {
        Schema::table('barang_masuk', function (Blueprint $table) {
            $table->dropColumn('foto_bukti');
        });

        Schema::table('barang_keluar', function (Blueprint $table) {
            $table->dropColumn('foto_bukti');
        });
    }
};
