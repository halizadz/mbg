<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\BarangMasuk;
use App\Models\ActivityLog;
use App\Http\Requests\StoreBarangMasukRequest;
use App\Services\StokService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Exception;

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
        $barang = Barang::orderBy('nama_barang')->get();
        return view('masuk.create', compact('barang'));
    }

    public function store(StoreBarangMasukRequest $request)
    {
        try {
            $validated = $request->validated();

            // Handle upload foto bukti (di luar transaction karena filesystem bukan transactional)
            $fotoBukti = null;
            if ($request->hasFile('foto_bukti')) {
                $fotoBukti = $request->file('foto_bukti')
                    ->store('bukti/masuk', 'public');
            }

            // Bungkus create + stok dalam satu transaction
            // Jika tambahStok gagal, record BarangMasuk otomatis di-rollback
            $masuk = DB::transaction(function () use ($validated, $fotoBukti) {
                $masuk = BarangMasuk::create([
                    'barang_id'  => $validated['barang_id'],
                    'user_id'    => auth()->id(),
                    'jumlah'     => $validated['jumlah'],
                    'tanggal'    => $validated['tanggal'],
                    'supplier'   => $validated['supplier'] ?? null,
                    'keterangan' => $validated['keterangan'] ?? null,
                    'foto_bukti' => $fotoBukti,
                ]);

                $this->stokService->tambahStok($validated['barang_id'], $validated['jumlah']);

                return $masuk;
            });

            ActivityLog::log('create', "Barang masuk: {$masuk->jumlah} unit", $masuk, null, $validated);

            return redirect()->route('transaksi.masuk')
                ->with('success', "Berhasil mencatat {$masuk->jumlah} barang masuk.");

        } catch (Exception $e) {
            // Hapus foto yang sudah terupload jika transaksi gagal
            if (isset($fotoBukti) && $fotoBukti) {
                Storage::disk('public')->delete($fotoBukti);
            }

            return back()->withInput()
                ->with('error', $e->getMessage());
        }
    }
}