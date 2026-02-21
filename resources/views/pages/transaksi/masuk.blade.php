@extends('layouts.app')

@section('title', 'Barang Masuk')
@section('breadcrumb', 'Beranda / Transaksi / Masuk')

@section('content')
<div class="wireframe-note mb-5">
    🔵 <strong>Barang Masuk:</strong> Catat penambahan stok barang ke gudang.
</div>

<div class="page-header mb-5">
    <h2 class="text-[22px] font-bold tracking-[-0.3px]">📥 Transaksi Barang Masuk</h2>
    <p class="text-[13px] mt-1" style="color:var(--text-secondary);">Catat penerimaan barang ke gudang</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
    <!-- Form -->
    <div class="section-card">
        <div class="section-head px-4 sm:px-5 py-3 sm:py-4">
            <div class="section-title">Form Barang Masuk</div>
        </div>

        <div class="p-4 sm:p-5">
            <form action="#" method="POST" class="space-y-4">
                @csrf

                <!-- Pilih Barang -->
                <div>
                    <label class="block text-[11px] sm:text-xs font-medium mb-1.5" style="color:var(--text-secondary);">
                        Pilih Barang <span class="text-danger">*</span>
                    </label>
                    <select name="barang_id" class="form-input w-full">
                        <option value="">-- Cari / Pilih Barang --</option>
                        <option value="1">BRG-001 — Kertas HVS A4 (Stok: 45 Rim)</option>
                        <option value="2">BRG-002 — Tinta Printer Canon (Stok: 2 Botol)</option>
                        <option value="8">BRG-008 — Spidol Whiteboard (Stok: 4 Pcs)</option>
                    </select>
                </div>

                <!-- Info Stok -->
                <div class="rounded-lg p-3 text-xs" style="background:rgba(16,185,129,0.08);border:1px solid rgba(16,185,129,0.25);color:#10b981;">
                    ✅ Stok akan bertambah setelah transaksi disimpan
                </div>

                <!-- Jumlah -->
                <div>
                    <label class="block text-[11px] sm:text-xs font-medium mb-1.5" style="color:var(--text-secondary);">
                        Jumlah Masuk <span class="text-danger">*</span>
                    </label>
                    <input type="number" name="jumlah" min="1" placeholder="0" class="form-input w-full">
                </div>

                <!-- Tanggal -->
                <div>
                    <label class="block text-[11px] sm:text-xs font-medium mb-1.5" style="color:var(--text-secondary);">
                        Tanggal Masuk <span class="text-danger">*</span>
                    </label>
                    <input type="date" name="tanggal" value="{{ date('Y-m-d') }}" class="form-input w-full">
                </div>

                <!-- Sumber / Supplier -->
                <div>
                    <label class="block text-[11px] sm:text-xs font-medium mb-1.5" style="color:var(--text-secondary);">
                        Sumber / Supplier
                    </label>
                    <input type="text" name="supplier" placeholder="Contoh: PT Gramedia, Tokopedia..." class="form-input w-full">
                </div>

                <!-- Keterangan -->
                <div>
                    <label class="block text-[11px] sm:text-xs font-medium mb-1.5" style="color:var(--text-secondary);">
                        Keterangan (opsional)
                    </label>
                    <textarea name="keterangan" rows="3" class="form-input w-full resize-none"
                              placeholder="Contoh: Restock bulanan, No. PO: 2026-001..."></textarea>
                </div>

                <!-- Tombol -->
                <div class="flex flex-col sm:flex-row gap-2 sm:gap-2.5 justify-end pt-3 mt-4" style="border-top:1px solid var(--border-color);">
                    <button type="reset" class="btn-secondary w-full sm:w-auto">Reset</button>
                    <button type="submit" class="btn-primary w-full sm:w-auto">
                        📥 Simpan Masuk
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Riwayat -->
    <div class="section-card">
        <div class="section-head px-4 sm:px-5 py-3 sm:py-4">
            <div class="section-title">Riwayat Masuk Hari Ini</div>
            <div class="section-sub">5 transaksi</div>
        </div>

        <div class="overflow-x-auto">
            <table class="data-table w-full">
                <thead>
                    <tr>
                        <th>Waktu</th>
                        <th>Barang</th>
                        <th>Jml</th>
                        <th>Stok Kini</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>13:15</td>
                        <td>Ballpoint Pilot</td>
                        <td class="mono text-success">+50</td>
                        <td class="mono">120</td>
                    </tr>
                    <tr>
                        <td>11:48</td>
                        <td>Binder Clip No.2</td>
                        <td class="mono text-success">+30</td>
                        <td class="mono">85</td>
                    </tr>
                    <tr>
                        <td>09:05</td>
                        <td>Map Plastik Bening</td>
                        <td class="mono text-success">+24</td>
                        <td class="mono">67</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="px-5 py-3 text-xs" style="border-top:1px solid var(--border-color);color:var(--text-secondary);">
            Total masuk hari ini: <strong class="text-success">+104 item</strong>
        </div>
    </div>
</div>
@endsection