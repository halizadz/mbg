<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Barang extends Model
{
    use SoftDeletes;

    protected $table = 'barangs';

    protected $fillable = [
        'kode_barang',
        'nama_barang',
        'kategori',
        'satuan',
        'stok',
        'stok_minimum',
        'keterangan',
    ];

    protected $casts = [
        'stok'         => 'integer',
        'stok_minimum' => 'integer',
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

    // Cek apakah stok menipis (di bawah atau sama dengan stok_minimum)
    public function stokMenipis(): bool
    {
        return $this->stok <= $this->stok_minimum;
    }

    // Status stok dalam bentuk teks
    public function statusStok(): string
    {
        if ($this->stok == 0) {
            return 'Habis';
        } elseif ($this->stok <= $this->stok_minimum) {
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