<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BarangKeluar extends Model
{
    protected $table = 'barang_keluar';

    protected $fillable = [
        'barang_id',
        'user_id',
        'jumlah',
        'tanggal',
        'keterangan',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'jumlah'  => 'integer',
    ];

    public function barang(): BelongsTo
    {
        return $this->belongsTo(Barang::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}