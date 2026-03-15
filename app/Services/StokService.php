<?php

namespace App\Services;

use App\Models\Barang;
use Illuminate\Support\Facades\DB;
use Exception;

class StokService
{
    /**
     * Tambah stok barang secara atomic.
     * Menggunakan raw SQL UPDATE untuk kompatibilitas penuh (SQLite + MySQL).
     */
    public function tambahStok(int $barangId, int $jumlah): Barang
    {
        return DB::transaction(function () use ($barangId, $jumlah) {
            // Atomic increment yang aman di semua DB engine (termasuk SQLite)
            $affected = DB::table('barangs')
                ->where('id', $barangId)
                ->whereNull('deleted_at')
                ->update(['stok' => DB::raw("stok + {$jumlah}")]);

            if ($affected === 0) {
                throw new Exception("Barang dengan ID {$barangId} tidak ditemukan.");
            }

            return Barang::findOrFail($barangId);
        });
    }

    /**
     * Kurangi stok barang secara atomic.
     * Validasi stok cukup dilakukan di level SQL WHERE clause (race-condition safe).
     */
    public function kurangiStok(int $barangId, int $jumlah): Barang
    {
        return DB::transaction(function () use ($barangId, $jumlah) {
            // Atomic decrement + validasi stok cukup dalam satu query
            // WHERE stok >= jumlah memastikan stok tidak pernah negatif
            $affected = DB::table('barangs')
                ->where('id', $barangId)
                ->whereNull('deleted_at')
                ->where('stok', '>=', $jumlah)
                ->update(['stok' => DB::raw("stok - {$jumlah}")]);

            if ($affected === 0) {
                $barang = Barang::find($barangId);
                if (!$barang) {
                    throw new Exception("Barang dengan ID {$barangId} tidak ditemukan.");
                }
                throw new Exception(
                    "Stok tidak cukup. Stok tersedia: {$barang->stok} {$barang->satuan}, diminta: {$jumlah} {$barang->satuan}."
                );
            }

            return Barang::findOrFail($barangId);
        });
    }

    /**
     * Cek stok tersedia tanpa mengubah data.
     */
    public function cekStok(int $barangId): int
    {
        return Barang::findOrFail($barangId)->stok;
    }
}