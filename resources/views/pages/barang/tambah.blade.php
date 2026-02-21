@extends('layouts.app')

@section('title', 'Tambah Barang')
@section('breadcrumb', 'Beranda / Data Barang / Tambah')

@section('content')
<div class="wireframe-note mb-5">
    🔵 <strong>Tambah Barang:</strong> Form input dengan validasi. Field kode barang auto-generate.
</div>

<div class="page-header mb-5">
    <h2 class="text-[22px] font-bold tracking-[-0.3px]">➕ Tambah Barang Baru</h2>
    <p class="text-[13px] text-[var(--text-secondary)] mt-1">Isi semua field yang diperlukan</p>
</div>

<div class="section-card max-w-full lg:max-w-[680px] mx-auto">
    <div class="section-head px-4 sm:px-5 py-3 sm:py-4">
        <div class="section-title text-sm font-semibold">Form Data Barang</div>
    </div>
    
    <div class="form-section p-4 sm:p-5">
        <form action="#" method="POST" class="space-y-4">
            @csrf
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Kode Barang -->
                <div class="form-group col-span-1">
                    <label class="form-label block text-[11px] sm:text-xs font-medium text-[var(--text-secondary)] mb-1.5">
                        Kode Barang <span class="text-danger">*</span>
                    </label>
                    <input type="text" name="kode" value="BRG-048" 
                           class="form-input w-full px-3 py-2 sm:py-[9px] bg-[var(--bg-primary)] border border-[var(--border-color)] rounded-lg text-[var(--text-primary)] text-xs sm:text-[13px] focus:border-accent/50 outline-none transition-colors">
                    <span class="text-[10px] sm:text-[11px] text-[var(--text-secondary)] mt-1 block">Auto-generate, bisa diedit manual</span>
                </div>

                <!-- Kategori -->
                <div class="form-group col-span-1">
                    <label class="form-label block text-[11px] sm:text-xs font-medium text-[var(--text-secondary)] mb-1.5">
                        Kategori <span class="text-danger">*</span>
                    </label>
                    <select name="kategori" class="form-input w-full px-3 py-2 sm:py-[9px] bg-[var(--bg-primary)] border border-[var(--border-color)] rounded-lg text-[var(--text-primary)] text-xs sm:text-[13px] focus:border-accent/50 outline-none">
                        <option value="">-- Pilih Kategori --</option>
                        <option>Alat Tulis Kantor</option>
                        <option>Elektronik</option>
                        <option>Kebersihan</option>
                        <option>Furniture</option>
                    </select>
                </div>

                <!-- Nama Barang -->
                <div class="form-group col-span-2">
                    <label class="form-label block text-[11px] sm:text-xs font-medium text-[var(--text-secondary)] mb-1.5">
                        Nama Barang <span class="text-danger">*</span>
                    </label>
                    <input type="text" name="nama" placeholder="Masukkan nama barang yang deskriptif"
                           class="form-input w-full px-3 py-2 sm:py-[9px] bg-[var(--bg-primary)] border border-[var(--border-color)] rounded-lg text-[var(--text-primary)] text-xs sm:text-[13px] focus:border-accent/50 outline-none">
                </div>

                <!-- Satuan -->
                <div class="form-group col-span-1">
                    <label class="form-label block text-[11px] sm:text-xs font-medium text-[var(--text-secondary)] mb-1.5">
                        Satuan <span class="text-danger">*</span>
                    </label>
                    <select name="satuan" class="form-input w-full px-3 py-2 sm:py-[9px] bg-[var(--bg-primary)] border border-[var(--border-color)] rounded-lg text-[var(--text-primary)] text-xs sm:text-[13px] focus:border-accent/50 outline-none">
                        <option>Pcs</option>
                        <option>Unit</option>
                        <option>Rim</option>
                        <option>Botol</option>
                        <option>Lusin</option>
                        <option>Pack</option>
                        <option>Kg</option>
                        <option>Lembar</option>
                    </select>
                </div>

                <!-- Stok Awal -->
                <div class="form-group col-span-1">
                    <label class="form-label block text-[11px] sm:text-xs font-medium text-[var(--text-secondary)] mb-1.5">
                        Stok Awal <span class="text-danger">*</span>
                    </label>
                    <input type="number" name="stok_awal" min="0" placeholder="0"
                           class="form-input w-full px-3 py-2 sm:py-[9px] bg-[var(--bg-primary)] border border-[var(--border-color)] rounded-lg text-[var(--text-primary)] text-xs sm:text-[13px] focus:border-accent/50 outline-none">
                </div>

                <!-- Stok Minimum -->
                <div class="form-group col-span-1 sm:col-span-2">
                    <label class="form-label block text-[11px] sm:text-xs font-medium text-[var(--text-secondary)] mb-1.5">
                        Stok Minimum (Alert) <span class="text-danger">*</span>
                    </label>
                    <input type="number" name="stok_minimum" min="1" placeholder="5"
                           class="form-input w-full px-3 py-2 sm:py-[9px] bg-[var(--bg-primary)] border border-[var(--border-color)] rounded-lg text-[var(--text-primary)] text-xs sm:text-[13px] focus:border-accent/50 outline-none">
                    <span class="text-[10px] sm:text-[11px] text-[var(--text-secondary)] mt-1 block">
                        Sistem akan alert bila stok ≤ nilai ini
                    </span>
                </div>

                <!-- Keterangan -->
                <div class="form-group col-span-2">
                    <label class="form-label block text-[11px] sm:text-xs font-medium text-[var(--text-secondary)] mb-1.5">
                        Keterangan (opsional)
                    </label>
                    <textarea name="keterangan" rows="3" 
                              class="form-input w-full px-3 py-2 sm:py-[9px] bg-[var(--bg-primary)] border border-[var(--border-color)] rounded-lg text-[var(--text-primary)] text-xs sm:text-[13px] focus:border-accent/50 outline-none resize-none"
                              placeholder="Deskripsi tambahan atau spesifikasi barang..."></textarea>
                </div>
            </div>

            <!-- Form Footer -->
            <div class="form-footer flex flex-col sm:flex-row gap-2 sm:gap-2.5 justify-end mt-6 pt-4 border-t border-[var(--border-color)]">
                <a href="{{ route('barang.index') }}" 
                   class="btn-secondary w-full sm:w-auto text-center px-4 sm:px-[18px] py-2 sm:py-[9px] text-xs sm:text-[13px] order-2 sm:order-1">
                    Batal
                </a>
                <button type="submit" 
                        class="btn-primary w-full sm:w-auto px-4 sm:px-[18px] py-2 sm:py-[9px] text-xs sm:text-[13px] order-1 sm:order-2">
                    💾 Simpan Barang
                </button>
            </div>
        </form>
    </div>
</div>
@endsection