<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('barangs', function (Blueprint $table) {
            $table->id();
            $table->string('kode_barang', 50)->unique(); 
            $table->string('nama_barang', 255);
            $table->string('kategori', 100);
            $table->string('satuan', 50);
            $table->unsignedInteger('stok')->default(0);
            $table->unsignedInteger('stok_minimum')->default(5);
            $table->text('keterangan')->nullable();
            $table->timestamps(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barangs');
    }
};
