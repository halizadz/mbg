<?php

namespace App\Services;

use App\Models\Barang;
use Illuminate\Support\Facades\DB;
use Exception;

class StokService
{
    /**
     * Tambah stok barang secara atomic
     */
    public function tambahStok(int $barangId, int $jumlah): Barang
    {
        return DB::transaction(function () use ($barangId, $jumlah) {
            $barang = Barang::lockForUpdate()->findOrFail($barangId);
            $barang->increment('stok', $jumlah);
            return $barang->fresh();
        });
    }

    /**
     * Kurangi stok barang secara atomic
     * Melempar Exception jika stok tidak cukup
     */
    public function kurangiStok(int $barangId, int $jumlah): Barang
    {
        return DB::transaction(function () use ($barangId, $jumlah) {
            $barang = Barang::lockForUpdate()->findOrFail($barangId);

            if ($barang->stok < $jumlah) {
                throw new Exception(
                    "Stok tidak cukup. Stok tersedia: {$barang->stok} {$barang->satuan}, diminta: {$jumlah} {$barang->satuan}."
                );
            }

            $barang->decrement('stok', $jumlah);
            return $barang->fresh();
        });
    }

    /**
     * Cek stok tersedia tanpa mengubah data
     */
    public function cekStok(int $barangId): int
    {
        return Barang::findOrFail($barangId)->stok;
    }
}