<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class BarangController extends Controller
{
    /**
     * Tampilkan daftar semua barang.
     */
    public function index(Request $request): View
    {
        $query = Barang::query();

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('kode_barang', 'like', "%{$search}%")
                  ->orWhere('nama_barang', 'like', "%{$search}%")
                  ->orWhere('kategori', 'like', "%{$search}%");
            });
        }

        // Filter kategori
        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        // Filter status stok
        if ($request->filled('status')) {
            match ($request->status) {
                'menipis' => $query->whereColumn('stok', '<=', 'stok_minimum')->where('stok', '>', 0),
                'habis'   => $query->where('stok', 0),
                'aman'    => $query->whereColumn('stok', '>', 'stok_minimum'),
                default   => null,
            };
        }

        $barang    = $query->orderBy('nama_barang')->paginate(15)->withQueryString();
        $kategori  = Barang::distinct()->pluck('kategori')->sort()->values();

        return view('pages.barang.index', compact('barang', 'kategori'));
    }

    /**
     * Tampilkan form tambah barang.
     */
    public function create(): View
    {
        $kategori = Barang::distinct()->pluck('kategori')->sort()->values();
        return view('pages.barang.tambah', compact('kategori'));
    }

    /**
     * Simpan barang baru.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'kode_barang'   => ['required', 'string', 'max:50', 'unique:barangs,kode_barang'],
            'nama_barang'   => ['required', 'string', 'max:255'],
            'kategori'      => ['required', 'string', 'max:100'],
            'satuan'        => ['required', 'string', 'max:50'],
            'stok'          => ['required', 'integer', 'min:0'],
            'stok_minimum'  => ['required', 'integer', 'min:0'],
            'keterangan'    => ['nullable', 'string', 'max:500'],
        ], [
            'kode_barang.required' => 'Kode barang harus diisi.',
            'kode_barang.unique'   => 'Kode barang sudah digunakan.',
            'nama_barang.required' => 'Nama barang harus diisi.',
            'kategori.required'    => 'Kategori harus diisi.',
            'satuan.required'      => 'Satuan harus diisi.',
            'stok.required'        => 'Stok harus diisi.',
            'stok_minimum.required'=> 'Stok minimum harus diisi.',
        ]);

        $barang = Barang::create($validated);

        ActivityLog::log('create', "Barang baru: {$barang->nama_barang}", $barang, null, $validated);

        return redirect()->route('barang.index')
            ->with('success', "Barang \"{$validated['nama_barang']}\" berhasil ditambahkan.");
    }

    /**
     * Tampilkan form edit barang.
     */
    public function edit(Barang $barang): View
    {
        $kategori = Barang::distinct()->pluck('kategori')->sort()->values();
        return view('pages.barang.edit', compact('barang', 'kategori'));
    }

    /**
     * Update data barang.
     */
    public function update(Request $request, Barang $barang): RedirectResponse
    {
        $validated = $request->validate([
            'kode_barang'   => ['required', 'string', 'max:50', "unique:barangs,kode_barang,{$barang->id}"],
            'nama_barang'   => ['required', 'string', 'max:255'],
            'kategori'      => ['required', 'string', 'max:100'],
            'satuan'        => ['required', 'string', 'max:50'],
            'stok_minimum'  => ['required', 'integer', 'min:0'],
            'keterangan'    => ['nullable', 'string', 'max:500'],
        ]);

        $oldValues = $barang->only(array_keys($validated));
        $barang->update($validated);

        ActivityLog::log('update', "Barang diperbarui: {$barang->nama_barang}", $barang, $oldValues, $validated);

        return redirect()->route('barang.index')
            ->with('success', "Barang \"{$barang->nama_barang}\" berhasil diperbarui.");
    }

    /**
     * Hapus barang (hanya jika tidak ada transaksi).
     */
    public function destroy(Barang $barang): RedirectResponse
    {
        // Cek apakah ada transaksi terkait
        if ($barang->barangMasuk()->exists() || $barang->barangKeluar()->exists()) {
            return back()->with('error', 'Barang tidak bisa dihapus karena memiliki riwayat transaksi.');
        }

        $nama = $barang->nama_barang;

        ActivityLog::log('delete', "Barang dihapus: {$nama}", $barang, $barang->toArray(), null);

        $barang->delete();

        return redirect()->route('barang.index')
            ->with('success', "Barang \"{$nama}\" berhasil dihapus.");
    }
    /**
     * Tampilkan daftar barang yang stoknya menipis.
     */
    public function menipis(): View
    {
        $barang = Barang::whereColumn('stok', '<=', 'stok_minimum')
            ->orderBy('stok')
            ->paginate(20);

        return view('stok.menipis', compact('barang'));
    }
}
