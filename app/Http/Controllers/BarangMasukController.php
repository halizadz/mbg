<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\BarangMasuk;
use App\Http\Requests\StoreBarangMasukRequest;
use App\Services\StokService;
use Illuminate\Http\Request;

class BarangMasukController extends Controller
{
    public function __construct(private StokService $stokService) {}

    public function index(Request $request)
    {
        $query = BarangMasuk::with(['barang', 'user'])
            ->orderBy('tanggal', 'desc')
            ->orderBy('created_at', 'desc');

        if ($request->filled('dari')) {
            $query->whereDate('tanggal', '>=', $request->dari);
        }
        if ($request->filled('sampai')) {
            $query->whereDate('tanggal', '<=', $request->sampai);
        }

        $riwayat = $query->paginate(10)->withQueryString();

        return view('masuk.index', compact('riwayat'));
    }

    public function create()
    {
        $barang = Barang::orderBy('nama')->get();
        return view('masuk.create', compact('barang'));
    }

    public function store(StoreBarangMasukRequest $request)
    {
        $validated = $request->validated();

        $masuk = BarangMasuk::create([
            'barang_id'  => $validated['barang_id'],
            'user_id'    => auth()->id() ?? 1,
            'jumlah'     => $validated['jumlah'],
            'tanggal'    => $validated['tanggal'],
            'supplier'   => $validated['supplier'] ?? null,
            'keterangan' => $validated['keterangan'] ?? null,
        ]);

        $this->stokService->tambahStok($validated['barang_id'], $validated['jumlah']);

        return redirect()->route('transaksi.masuk')
            ->with('success', "Berhasil mencatat {$masuk->jumlah} barang masuk.");
    }
}