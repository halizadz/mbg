@extends('layouts.app')

@section('title', 'Barang Keluar')
@section('breadcrumb', 'Beranda / Transaksi / Keluar')

@section('content')
<div class="wireframe-note mb-5">
    🔵 <strong>Barang Keluar:</strong> Validasi stok real-time, tidak bisa keluar melebihi stok tersedia.
</div>

<div class="page-header mb-5">
    <h2 class="text-[22px] font-bold tracking-[-0.3px]">📤 Transaksi Barang Keluar</h2>
    <p class="text-[13px] text-[var(--text-secondary)] mt-1">Catat pengeluaran barang dari gudang</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
    <!-- Form -->
    <div class="section-card">
        <div class="section-head px-4 sm:px-5 py-3 sm:py-4">
            <div class="section-title text-sm font-semibold">Form Barang Keluar</div>
        </div>
        
        <div class="form-section p-4 sm:p-5">
            <form action="#" method="POST" class="space-y-4">
                @csrf
                
                <!-- Pilih Barang -->
                <div class="form-group">
                    <label class="form-label block text-[11px] sm:text-xs font-medium text-[var(--text-secondary)] mb-1.5">
                        Pilih Barang <span class="text-danger">*</span>
                    </label>
                    <select name="barang_id" class="form-input w-full px-3 py-2 sm:py-[9px] bg-[var(--bg-primary)] border border-[var(--border-color)] rounded-lg text-[var(--text-primary)] text-xs sm:text-[13px] focus:border-accent/50 outline-none">
                        <option value="">-- Cari / Pilih Barang --</option>
                        <option value="1">BRG-001 — Kertas HVS A4 (Stok: 45 Rim)</option>
                        <option value="2">BRG-002 — Tinta Printer Canon (Stok: 2 Botol)</option>
                        <option value="8">BRG-008 — Spidol Whiteboard (Stok: 4 Pcs)</option>
                    </select>
                </div>

                <!-- Info Stok -->
                <div class="bg-danger/8 border border-danger/25 rounded-lg p-3 text-xs text-danger">
                    ⚠ Stok tersedia: <strong class="font-mono">2 Botol</strong> — pastikan tidak melebihi stok!
                </div>

                <!-- Jumlah -->
                <div class="form-group">
                    <label class="form-label block text-[11px] sm:text-xs font-medium text-[var(--text-secondary)] mb-1.5">
                        Jumlah Keluar <span class="text-danger">*</span>
                    </label>
                    <input type="number" name="jumlah" min="1" max="2" placeholder="0"
                           class="form-input w-full px-3 py-2 sm:py-[9px] bg-[var(--bg-primary)] border border-[var(--border-color)] rounded-lg text-[var(--text-primary)] text-xs sm:text-[13px] focus:border-accent/50 outline-none">
                </div>

                <!-- Tanggal -->
                <div class="form-group">
                    <label class="form-label block text-[11px] sm:text-xs font-medium text-[var(--text-secondary)] mb-1.5">
                        Tanggal Keluar <span class="text-danger">*</span>
                    </label>
                    <input type="date" name="tanggal" value="{{ date('Y-m-d') }}"
                           class="form-input w-full px-3 py-2 sm:py-[9px] bg-[var(--bg-primary)] border border-[var(--border-color)] rounded-lg text-[var(--text-primary)] text-xs sm:text-[13px] focus:border-accent/50 outline-none">
                </div>

                <!-- Keterangan -->
                <div class="form-group">
                    <label class="form-label block text-[11px] sm:text-xs font-medium text-[var(--text-secondary)] mb-1.5">
                        Keterangan (opsional)
                    </label>
                    <textarea name="keterangan" rows="3"
                              class="form-input w-full px-3 py-2 sm:py-[9px] bg-[var(--bg-primary)] border border-[var(--border-color)] rounded-lg text-[var(--text-primary)] text-xs sm:text-[13px] focus:border-accent/50 outline-none resize-none"
                              placeholder="Contoh: Digunakan oleh Dept. HRD, pemohon: Budi S."></textarea>
                </div>

                <!-- Tombol -->
                <div class="form-footer flex flex-col sm:flex-row gap-2 sm:gap-2.5 justify-end pt-3 border-t border-[var(--border-color)] mt-4">
                    <button type="reset" 
                            class="btn-secondary w-full sm:w-auto px-4 sm:px-[18px] py-2 sm:py-[9px] text-xs sm:text-[13px]">
                        Reset
                    </button>
                    <button type="submit" 
                            class="btn-primary w-full sm:w-auto px-4 sm:px-[18px] py-2 sm:py-[9px] text-xs sm:text-[13px] bg-danger hover:bg-red-600">
                        📤 Simpan Keluar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Riwayat -->
    <div class="section-card">
        <div class="section-head px-4 sm:px-5 py-3 sm:py-4">
            <div class="section-title text-sm font-semibold">Riwayat Keluar Hari Ini</div>
            <div class="section-sub text-[11px] sm:text-xs text-[var(--text-secondary)]">3 transaksi</div>
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
                    <tr>
                        <td>14:32</td>
                        <td>Kertas HVS A4</td>
                        <td class="mono text-danger">-10</td>
                        <td class="mono text-danger">2</td>
                    </tr>
                    <tr>
                        <td>12:10</td>
                        <td>Tinta Printer Hitam</td>
                        <td class="mono text-danger">-5</td>
                        <td class="mono text-danger">1</td>
                    </tr>
                    <tr>
                        <td>08:45</td>
                        <td>Spidol Whiteboard</td>
                        <td class="mono text-danger">-6</td>
                        <td class="mono text-warning">4</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="pagination px-5 py-3 border-t border-[var(--border-color)] text-xs text-[var(--text-secondary)]">
            <span>Total keluar hari ini: <strong class="text-danger">-21 item</strong></span>
        </div>
    </div>
</div>
@endsection