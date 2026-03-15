<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\BarangMasuk;
use App\Models\BarangKeluar;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $today     = Carbon::today();
        $yesterday = Carbon::yesterday();
        $weekAgo   = Carbon::today()->subDays(6);

        // Statistik utama (2 query instead of 7)
        $barangStats = Barang::selectRaw('
            SUM(stok) as total_stok,
            COUNT(*) as total_jenis,
            SUM(CASE WHEN stok <= stok_minimum THEN 1 ELSE 0 END) as stok_menipis
        ')->first();

        $totalStok        = (int) $barangStats->total_stok;
        $totalJenisBarang = (int) $barangStats->total_jenis;
        $stokMenipis      = (int) $barangStats->stok_menipis;

        // Masuk hari ini + kemarin (1 query instead of 4)
        $masukStats = BarangMasuk::selectRaw("
            SUM(CASE WHEN DATE(tanggal) = ? THEN jumlah ELSE 0 END) as hari_ini_jumlah,
            SUM(CASE WHEN DATE(tanggal) = ? THEN 1 ELSE 0 END) as hari_ini_tx,
            SUM(CASE WHEN DATE(tanggal) = ? THEN jumlah ELSE 0 END) as kemarin_jumlah
        ", [$today->toDateString(), $today->toDateString(), $yesterday->toDateString()])
        ->first();

        $masukHariIni   = (int) $masukStats->hari_ini_jumlah;
        $txMasukHariIni = (int) $masukStats->hari_ini_tx;
        $masukKemarin   = (int) $masukStats->kemarin_jumlah;

        // Keluar hari ini + kemarin (1 query instead of 4)
        $keluarStats = BarangKeluar::selectRaw("
            SUM(CASE WHEN DATE(tanggal) = ? THEN jumlah ELSE 0 END) as hari_ini_jumlah,
            SUM(CASE WHEN DATE(tanggal) = ? THEN 1 ELSE 0 END) as hari_ini_tx,
            SUM(CASE WHEN DATE(tanggal) = ? THEN jumlah ELSE 0 END) as kemarin_jumlah
        ", [$today->toDateString(), $today->toDateString(), $yesterday->toDateString()])
        ->first();

        $keluarHariIni   = (int) $keluarStats->hari_ini_jumlah;
        $txKeluarHariIni = (int) $keluarStats->hari_ini_tx;
        $keluarKemarin   = (int) $keluarStats->kemarin_jumlah;

        $diffMasuk  = $masukHariIni - $masukKemarin;
        $diffKeluar = $keluarHariIni - $keluarKemarin;

        // Chart 7 hari terakhir (1 query each instead of 7 each = 2 instead of 14)
        $chartMasukData = BarangMasuk::selectRaw('DATE(tanggal) as tgl, SUM(jumlah) as total')
            ->whereBetween('tanggal', [$weekAgo, $today])
            ->groupByRaw('DATE(tanggal)')
            ->pluck('total', 'tgl');

        $chartKeluarData = BarangKeluar::selectRaw('DATE(tanggal) as tgl, SUM(jumlah) as total')
            ->whereBetween('tanggal', [$weekAgo, $today])
            ->groupByRaw('DATE(tanggal)')
            ->pluck('total', 'tgl');

        $chartDays   = [];
        $chartMasuk  = [];
        $chartKeluar = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $dateStr = $date->toDateString();
            $chartDays[]   = $i === 0 ? 'Hari ini' : $date->translatedFormat('D');
            $chartMasuk[]  = (int) ($chartMasukData[$dateStr] ?? 0);
            $chartKeluar[] = (int) ($chartKeluarData[$dateStr] ?? 0);
        }

        // Alert stok menipis
        $alertStok = Barang::whereColumn('stok', '<=', 'stok_minimum')
            ->orderBy('stok')
            ->take(5)
            ->get();

        // Transaksi terbaru hari ini
        $transaksiMasuk = BarangMasuk::with(['barang', 'user'])
            ->whereDate('tanggal', $today)
            ->latest('created_at')
            ->take(5)
            ->get()
            ->map(fn ($item) => [
                'waktu'  => optional($item->created_at)->format('H:i') ?? '-',
                'kode'   => optional($item->barang)->kode_barang ?? '-',
                'nama'   => optional($item->barang)->nama_barang ?? '-',
                'tipe'   => 'Masuk',
                'jumlah' => $item->jumlah,
                'stok'   => optional($item->barang)->stok ?? 0,
                'admin'  => optional($item->user)->name ?? 'Admin',
            ]);

        $transaksiKeluar = BarangKeluar::with(['barang', 'user'])
            ->whereDate('tanggal', $today)
            ->latest('created_at')
            ->take(5)
            ->get()
            ->map(fn ($item) => [
                'waktu'  => optional($item->created_at)->format('H:i') ?? '-',
                'kode'   => optional($item->barang)->kode_barang ?? '-',
                'nama'   => optional($item->barang)->nama_barang ?? '-',
                'tipe'   => 'Keluar',
                'jumlah' => $item->jumlah,
                'stok'   => optional($item->barang)->stok ?? 0,
                'admin'  => optional($item->user)->name ?? 'Admin',
            ]);

        $transaksiTerbaru = $transaksiMasuk->concat($transaksiKeluar)
            ->sortByDesc('waktu')
            ->take(10)
            ->values();

        return view('dashboard', compact(
            'totalStok', 'totalJenisBarang',
            'masukHariIni', 'txMasukHariIni',
            'keluarHariIni', 'txKeluarHariIni',
            'stokMenipis', 'diffMasuk', 'diffKeluar',
            'chartDays', 'chartMasuk', 'chartKeluar',
            'alertStok', 'transaksiTerbaru',
        ));
    }
}
