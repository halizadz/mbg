<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\BarangKeluar;
use App\Models\ActivityLog;
use App\Http\Requests\StoreBarangKeluarRequest;
use App\Services\StokService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
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
        $barang = Barang::where('stok', '>', 0)->orderBy('nama_barang')->get();
        return view('keluar.create', compact('barang'));
    }

    public function store(StoreBarangKeluarRequest $request)
    {
        try {
            $validated = $request->validated();

            // Handle upload foto bukti (di luar transaction karena filesystem bukan transactional)
            $fotoBukti = null;
            if ($request->hasFile('foto_bukti')) {
                $fotoBukti = $request->file('foto_bukti')
                    ->store('bukti/keluar', 'public');
            }

            // Bungkus create + stok dalam satu transaction
            // Jika kurangiStok gagal, record BarangKeluar otomatis di-rollback
            $keluar = DB::transaction(function () use ($validated, $fotoBukti) {
                $keluar = BarangKeluar::create([
                    'barang_id'  => $validated['barang_id'],
                    'user_id'    => auth()->id(),
                    'jumlah'     => $validated['jumlah'],
                    'tanggal'    => $validated['tanggal'],
                    'keterangan' => $validated['keterangan'] ?? null,
                    'foto_bukti' => $fotoBukti,
                ]);

                $this->stokService->kurangiStok($validated['barang_id'], $validated['jumlah']);

                return $keluar;
            });

            ActivityLog::log('create', "Barang keluar: {$keluar->jumlah} unit", $keluar, null, $validated);

            return redirect()->route('transaksi.keluar')
                ->with('success', "Berhasil mencatat {$keluar->jumlah} barang keluar.");

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