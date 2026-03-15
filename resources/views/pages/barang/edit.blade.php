@extends('layouts.app')

@section('title', 'Edit Barang')
@section('breadcrumb', 'Beranda / Data Barang / Edit')

@section('content')

<div class="flex items-center justify-between mb-5">
    <div>
        <h2 class="text-[22px] font-bold tracking-[-0.3px]"><i class="fas fa-edit text-blue-500 mr-2"></i> Edit Barang</h2>
        <p class="text-[13px] mt-1" style="color:var(--text-secondary);">Ubah data master barang</p>
    </div>
    <a href="{{ route('barang.index') }}" class="btn-secondary text-sm px-4 py-2">&larr; Kembali</a>
</div>

<div class="section-card max-w-3xl">
    <div class="section-head px-4 sm:px-5 py-3 sm:py-4">
        <div class="section-title">Form Edit Barang: {{ $barang->kode_barang }}</div>
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

        <form action="{{ route('barang.update', $barang->id) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                {{-- Kode Barang --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                        Kode Barang <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="kode_barang" value="{{ old('kode_barang', $barang->kode_barang) }}" class="form-input w-full uppercase" required autofocus>
                </div>

                {{-- Nama Barang --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                        Nama Lengkap Barang <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="nama_barang" value="{{ old('nama_barang', $barang->nama_barang) }}" class="form-input w-full" required>
                </div>

                {{-- Kategori --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                        Kategori <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="kategori" list="kategori-list" value="{{ old('kategori', $barang->kategori) }}" class="form-input w-full" required>
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
                    <input type="text" name="satuan" value="{{ old('satuan', $barang->satuan) }}" class="form-input w-full" required>
                </div>

                {{-- Stok (Readonly in Edit) --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                        Stok Saat Ini <span class="text-gray-400 font-normal ml-1">(Buka transaksi untuk mengubah)</span>
                    </label>
                    <input type="text" value="{{ $barang->stok }}" class="form-input w-full font-mono bg-gray-50 text-gray-500 cursor-not-allowed" readonly disabled>
                </div>

                {{-- Stok Minimum --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                        Stok Minimum (Batas Peringatan) <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="stok_minimum" value="{{ old('stok_minimum', $barang->stok_minimum) }}" min="0" class="form-input w-full font-mono" required>
                </div>
            </div>

            {{-- Keterangan --}}
            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                    Keterangan (Opsional)
                </label>
                <textarea name="keterangan" rows="3" class="form-input w-full resize-none">{{ old('keterangan', $barang->keterangan) }}</textarea>
            </div>

            {{-- Tombol --}}
            <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                <a href="{{ route('barang.index') }}" class="btn-secondary px-5 py-2.5 text-sm font-medium">Batal</a>
                <button type="submit" class="btn-primary px-6 py-2.5 text-sm font-medium"><i class="fas fa-save mr-2"></i> Update Barang</button>
            </div>
        </form>
    </div>
</div>

@endsection
