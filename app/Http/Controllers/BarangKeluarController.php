<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\BarangKeluar;
use App\Http\Requests\StoreBarangKeluarRequest;
use App\Services\StokService;
use Illuminate\Http\Request;
use Exception;

class BarangKeluarController extends Controller
{
    public function __construct(private StokService $stokService) {}

    public function index(Request $request)
    {
        $query = BarangKeluar::with(['barang', 'user'])
            ->orderBy('tanggal', 'desc')
            ->orderBy('created_at', 'desc');

        if ($request->filled('dari')) {
            $query->whereDate('tanggal', '>=', $request->dari);
        }
        if ($request->filled('sampai')) {
            $query->whereDate('tanggal', '<=', $request->sampai);
        }

        $riwayat = $query->paginate(10)->withQueryString();

        return view('keluar.index', compact('riwayat'));
    }

    public function create()
    {
        $barang = Barang::where('stok', '>', 0)->orderBy('nama')->get();
        return view('keluar.create', compact('barang'));
    }

    public function store(StoreBarangKeluarRequest $request)
    {
        try {
            $validated = $request->validated();

            $keluar = BarangKeluar::create([
                'barang_id'  => $validated['barang_id'],
                'user_id'    => auth()->id() ?? 1,
                'jumlah'     => $validated['jumlah'],
                'tanggal'    => $validated['tanggal'],
                'keterangan' => $validated['keterangan'] ?? null,
            ]);

            $this->stokService->kurangiStok($validated['barang_id'], $validated['jumlah']);

            return redirect()->route('transaksi.keluar')
                ->with('success', "Berhasil mencatat {$keluar->jumlah} barang keluar.");

        } catch (Exception $e) {
            return back()->withInput()
                ->with('error', $e->getMessage());
        }
    }
}