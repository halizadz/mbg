<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BarangMasuk;
use App\Models\BarangKeluar;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->input('start_date', now()->startOfMonth()->toDateString());
        $endDate   = $request->input('end_date', now()->toDateString());
        $type      = $request->input('type', 'all');

        $transaksi = $this->buildTransaksi($startDate, $endDate, $type);

        // Paginate manually
        $page    = $request->input('page', 1);
        $perPage = 15;
        $paginated = new LengthAwarePaginator(
            $transaksi->forPage($page, $perPage),
            $transaksi->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('laporan.index', [
            'transaksi' => $paginated,
            'startDate' => $startDate,
            'endDate'   => $endDate,
            'type'      => $type,
        ]);
    }

    /**
     * Export laporan ke format PDF.
     */
    public function exportPdf(Request $request)
    {
        $request->validate([
            'start_date' => 'nullable|date',
            'end_date'   => 'nullable|date|after_or_equal:start_date',
        ]);

        $startDate = $request->input('start_date', now()->startOfMonth()->toDateString());
        $endDate   = $request->input('end_date', now()->toDateString());
        $type      = $request->input('type', 'all');

        $transaksi = $this->buildTransaksi($startDate, $endDate, $type);
        $typeLabel = $this->resolveTypeLabel($type);

        $pdf = Pdf::loadView('laporan.pdf', [
            'transaksi'   => $transaksi,
            'startDate'   => $startDate,
            'endDate'     => $endDate,
            'typeLabel'   => $typeLabel,
            'generatedAt' => now()->format('d M Y H:i'),
        ])->setPaper('a4', 'landscape');

        $filename = 'laporan-transaksi-' . $startDate . '-sd-' . $endDate . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Tampilkan laporan format PDF HTML untuk dicetak (Print).
     */
    public function print(Request $request)
    {
        $request->validate([
            'start_date' => 'nullable|date',
            'end_date'   => 'nullable|date|after_or_equal:start_date',
        ]);

        $startDate = $request->input('start_date', now()->startOfMonth()->toDateString());
        $endDate   = $request->input('end_date', now()->toDateString());
        $type      = $request->input('type', 'all');

        $transaksi = $this->buildTransaksi($startDate, $endDate, $type);
        $typeLabel = $this->resolveTypeLabel($type);

        return view('laporan.pdf', [
            'transaksi'   => $transaksi,
            'startDate'   => $startDate,
            'endDate'     => $endDate,
            'typeLabel'   => $typeLabel,
            'generatedAt' => now()->format('d M Y H:i'),
            'isPrint'     => true,
        ]);
    }

    // ==================== PRIVATE HELPERS ====================

    /**
     * Bangun Collection transaksi gabungan (masuk + keluar) berdasarkan filter.
     * Dipanggil oleh index(), exportPdf(), dan print() — tulis sekali, pakai di mana saja.
     */
    private function buildTransaksi(string $startDate, string $endDate, string $type): Collection
    {
        $transaksi = collect([]);

        if (in_array($type, ['all', 'masuk'])) {
            $masuk = BarangMasuk::with(['barang', 'user'])
                ->whereBetween('tanggal', [$startDate, $endDate])
                ->get()
                ->map(fn ($item) => [
                    'tanggal_raw' => $item->tanggal,
                    'tanggal'     => $item->tanggal ? $item->tanggal->format('d M Y') : '-',
                    'kode'        => optional($item->barang)->kode_barang ?? '-',
                    'nama'        => optional($item->barang)->nama_barang ?? '-',
                    'tipe'        => 'Masuk',
                    'tipeColor'   => 'green',
                    'jml'         => $item->jumlah,
                    'stok'        => optional($item->barang)->stok ?? 0,
                    'ket'         => $item->keterangan ?? '-',
                    'admin'       => optional($item->user)->name ?? 'System',
                    'foto_bukti'  => $item->foto_bukti,
                    'created_at'  => $item->created_at,
                ]);

            $transaksi = $transaksi->merge($masuk);
        }

        if (in_array($type, ['all', 'keluar'])) {
            $keluar = BarangKeluar::with(['barang', 'user'])
                ->whereBetween('tanggal', [$startDate, $endDate])
                ->get()
                ->map(fn ($item) => [
                    'tanggal_raw' => $item->tanggal,
                    'tanggal'     => $item->tanggal ? $item->tanggal->format('d M Y') : '-',
                    'kode'        => optional($item->barang)->kode_barang ?? '-',
                    'nama'        => optional($item->barang)->nama_barang ?? '-',
                    'tipe'        => 'Keluar',
                    'tipeColor'   => 'red',
                    'jml'         => $item->jumlah,
                    'stok'        => optional($item->barang)->stok ?? 0,
                    'ket'         => $item->keterangan ?? '-',
                    'admin'       => optional($item->user)->name ?? 'System',
                    'foto_bukti'  => $item->foto_bukti,
                    'created_at'  => $item->created_at,
                ]);

            $transaksi = $transaksi->merge($keluar);
        }

        return $transaksi->sortByDesc('created_at')->values();
    }

    /**
     * Resolve label tipe transaksi untuk tampilan.
     */
    private function resolveTypeLabel(string $type): string
    {
        return match ($type) {
            'masuk'  => 'Barang Masuk',
            'keluar' => 'Barang Keluar',
            default  => 'Semua Transaksi',
        };
    }
}
