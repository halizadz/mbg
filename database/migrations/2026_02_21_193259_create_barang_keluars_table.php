<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('barang_keluar', function (Blueprint $table) {
            $table->id();
            $table->foreignId('barang_id')
                  ->constrained('barangs')
                  ->onDelete('restrict');
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->onDelete('restrict');
            $table->unsignedInteger('jumlah');
            $table->date('tanggal');
            $table->string('supplier')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('barang_masuk');
    }
};