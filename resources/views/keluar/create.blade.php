@extends('layouts.app')

@section('title', 'Tambah Barang Keluar')
@section('breadcrumb', 'Beranda / Transaksi / Keluar / Tambah')

@section('content')

<div class="flex items-center justify-between mb-5">
    <div>
        <h2 class="text-[22px] font-bold tracking-[-0.3px]">📤 Tambah Barang Keluar</h2>
        <p class="text-[13px] mt-1" style="color:var(--text-secondary);">Catat pengeluaran barang dari gudang</p>
    </div>
    <a href="{{ route('transaksi.keluar') }}" class="btn-secondary text-sm px-4 py-2">← Kembali</a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
    {{-- Form --}}
    <div class="section-card">
        <div class="section-head px-4 sm:px-5 py-3 sm:py-4">
            <div class="section-title">Form Barang Keluar</div>
        </div>

        <div class="p-4 sm:p-5">
            @if($errors->any())
            <div class="mb-4 px-4 py-3 rounded-lg text-sm" style="background:rgba(239,68,68,0.15);border:1px solid rgba(239,68,68,0.3);color:#ef4444;">
                <ul class="list-disc list-inside space-y-1">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form action="{{ route('transaksi.keluar.store') }}" method="POST" class="space-y-4">
                @csrf

                {{-- Pilih Barang --}}
                <div>
                    <label class="block text-xs font-medium mb-1.5" style="color:var(--text-secondary);">
                        Pilih Barang <span style="color:#ef4444;">*</span>
                    </label>
                    <select name="barang_id" id="barang_id" class="form-input w-full" required>
                        <option value="">-- Pilih Barang --</option>
                        @foreach($barang as $b)
                        <option value="{{ $b->id }}"
                            data-stok="{{ $b->stok }}"
                            data-satuan="{{ $b->satuan }}"
                            {{ old('barang_id') == $b->id ? 'selected' : '' }}>
                            {{ $b->kode }} — {{ $b->nama }} (Stok: {{ $b->stok }} {{ $b->satuan }})
                        </option>
                        @endforeach
                    </select>
                </div>

                {{-- Warning stok --}}
                <div id="stokWarning" class="rounded-lg p-3 text-xs hidden"
                     style="background:rgba(239,68,68,0.08);border:1px solid rgba(239,68,68,0.25);color:#ef4444;">
                    ⚠️ Stok tersedia: <strong class="font-mono" id="stokNow">-</strong> — pastikan tidak melebihi stok!
                </div>

                {{-- Jumlah --}}
                <div>
                    <label class="block text-xs font-medium mb-1.5" style="color:var(--text-secondary);">
                        Jumlah Keluar <span style="color:#ef4444;">*</span>
                    </label>
                    <input type="number" name="jumlah" id="jumlah" min="1"
                           value="{{ old('jumlah') }}" placeholder="0"
                           class="form-input w-full" required>
                    <p id="jumlahError" class="text-xs mt-1 hidden" style="color:#ef4444;"></p>
                </div>

                {{-- Tanggal --}}
                <div>
                    <label class="block text-xs font-medium mb-1.5" style="color:var(--text-secondary);">
                        Tanggal Keluar <span style="color:#ef4444;">*</span>
                    </label>
                    <input type="date" name="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}"
                           max="{{ date('Y-m-d') }}" class="form-input w-full" required>
                </div>

                {{-- Keterangan --}}
                <div>
                    <label class="block text-xs font-medium mb-1.5" style="color:var(--text-secondary);">
                        Keterangan (opsional)
                    </label>
                    <textarea name="keterangan" rows="3" class="form-input w-full resize-none"
                              placeholder="Contoh: Digunakan oleh Dept. HRD, pemohon: Budi S.">{{ old('keterangan') }}</textarea>
                </div>

                {{-- Tombol --}}
                <div class="flex flex-col sm:flex-row gap-2 justify-end pt-3"
                     style="border-top:1px solid var(--border-color);">
                    <button type="reset" class="btn-secondary w-full sm:w-auto px-5 py-2 text-sm"
                            onclick="resetForm()">Reset</button>
                    <button type="submit" id="btnSubmit"
                            class="btn-primary w-full sm:w-auto px-5 py-2 text-sm"
                            style="background:#ef4444;">
                        📤 Simpan Keluar
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Riwayat hari ini --}}
    <div class="section-card">
        <div class="section-head px-4 sm:px-5 py-3 sm:py-4">
            <div class="section-title">Riwayat Keluar Hari Ini</div>
        </div>
        <div class="overflow-x-auto">
            <table class="data-table w-full">
                <thead>
                    <tr>
                        <th>Waktu</th>
                        <th>Barang</th>
                        <th>Jml</th>
                        <th>Stok Sisa</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                    $hariIni = \App\Models\BarangKeluar::with('barang')
                        ->whereDate('tanggal', today())
                        ->latest()
                        ->take(10)
                        ->get();
                    @endphp
                    @forelse($hariIni as $item)
                    <tr>
                        <td class="mono text-xs">{{ $item->created_at->format('H:i') }}</td>
                        <td>{{ $item->barang->nama ?? '-' }}</td>
                        <td class="mono font-bold" style="color:#ef4444;">-{{ $item->jumlah }}</td>
                        <td class="mono {{ ($item->barang->stok ?? 0) <= ($item->barang->min_stok ?? 0) ? 'text-danger' : '' }}">
                            {{ $item->barang->stok ?? '-' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-6 text-xs" style="color:var(--text-secondary);">
                            Belum ada transaksi hari ini.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($hariIni->isNotEmpty())
        <div class="px-5 py-3 text-xs" style="border-top:1px solid var(--border-color);color:var(--text-secondary);">
            Total keluar hari ini: <strong style="color:#ef4444;">-{{ $hariIni->sum('jumlah') }} item</strong>
        </div>
        @endif
    </div>
</div>

<script src="{{ asset('js/transaksi.js') }}"></script>
@endsection