<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Barang extends Model
{
    protected $table = 'barang';

    protected $fillable = [
        'kode',
        'nama',
        'kategori',
        'satuan',
        'stok',
        'min_stok',
        'keterangan',
    ];

    protected $casts = [
        'stok'     => 'integer',
        'min_stok' => 'integer',
    ];

    // ==================== RELASI ====================

    public function barangMasuk(): HasMany
    {
        return $this->hasMany(BarangMasuk::class);
    }

    public function barangKeluar(): HasMany
    {
        return $this->hasMany(BarangKeluar::class);
    }

    // ==================== HELPER ====================

    // Cek apakah stok menipis (di bawah atau sama dengan min_stok)
    public function stokMenipis(): bool
    {
        return $this->stok <= $this->min_stok;
    }

    // Status stok dalam bentuk teks
    public function statusStok(): string
    {
        if ($this->stok == 0) {
            return 'Habis';
        } elseif ($this->stok <= $this->min_stok) {
            return 'Menipis';
        } else {
            return 'Aman';
        }
    }

    // Warna badge status untuk tampilan
    public function warnaStatus(): string
    {
        return match($this->statusStok()) {
            'Habis'   => 'red',
            'Menipis' => 'yellow',
            'Aman'    => 'green',
        };
    }
}