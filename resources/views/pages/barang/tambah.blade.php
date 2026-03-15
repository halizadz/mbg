@extends('layouts.app')

@section('title', 'Tambah Barang')
@section('breadcrumb', 'Beranda / Data Barang / Tambah')

@section('content')

<div class="flex items-center justify-between mb-5">
    <div>
        <h2 class="text-[22px] font-bold tracking-[-0.3px]"><i class="fas fa-box-open text-accent mr-2"></i> Tambah Barang Baru</h2>
        <p class="text-[13px] mt-1" style="color:var(--text-secondary);">Masukkan data barang baru ke inventaris</p>
    </div>
    <a href="{{ route('barang.index') }}" class="btn-secondary text-sm px-4 py-2">&larr; Kembali</a>
</div>

<div class="section-card max-w-3xl">
    <div class="section-head px-4 sm:px-5 py-3 sm:py-4">
        <div class="section-title">Form Tambah Barang</div>
    </div>

    <div class="p-4 sm:p-5">
        @if($errors->any())
        <div class="mb-5 px-4 py-3 rounded-lg text-sm bg-red-50 border border-red-200 text-red-600">
            <ul class="list-disc list-inside space-y-1">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('barang.store') }}" method="POST" class="space-y-5">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                {{-- Kode Barang --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                        Kode Barang <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="kode_barang" value="{{ old('kode_barang') }}" placeholder="Contoh: BRG-001" class="form-input w-full uppercase" required autofocus>
                </div>

                {{-- Nama Barang --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                        Nama Lengkap Barang <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="nama_barang" value="{{ old('nama_barang') }}" placeholder="Contoh: Beras Premium 5Kg" class="form-input w-full" required>
                </div>

                {{-- Kategori --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                        Kategori <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="kategori" list="kategori-list" value="{{ old('kategori') }}" placeholder="Pilih atau ketik kategori baru" class="form-input w-full" required>
                    <datalist id="kategori-list">
                        @foreach($kategori as $kat)
                            <option value="{{ $kat }}">
                        @endforeach
                    </datalist>
                </div>

                {{-- Satuan --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                        Satuan <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="satuan" value="{{ old('satuan', 'Pcs') }}" placeholder="Contoh: Kg, Liter, Pcs" class="form-input w-full" required>
                </div>

                {{-- Stok Awal --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                        Stok Awal <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="stok" value="{{ old('stok', 0) }}" min="0" class="form-input w-full font-mono" required>
                </div>

                {{-- Stok Minimum --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                        Stok Minimum (Batas Peringatan) <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="stok_minimum" value="{{ old('stok_minimum', 5) }}" min="0" class="form-input w-full font-mono" required>
                </div>
            </div>

            {{-- Keterangan --}}
            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                    Keterangan (Opsional)
                </label>
                <textarea name="keterangan" rows="3" class="form-input w-full resize-none" placeholder="Catatan tambahan mengenai barang ini...">{{ old('keterangan') }}</textarea>
            </div>

            {{-- Tombol --}}
            <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                <button type="reset" class="btn-secondary px-5 py-2.5 text-sm font-medium">Reset</button>
                <button type="submit" class="btn-primary px-6 py-2.5 text-sm font-medium"><i class="fas fa-save mr-2"></i> Simpan Barang</button>
            </div>
        </form>
    </div>
</div>

@endsection
