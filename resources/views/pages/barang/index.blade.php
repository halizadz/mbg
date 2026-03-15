@extends('layouts.app')

@section('title', 'Data Barang')
@section('breadcrumb', 'Beranda / Data Barang')

@section('content')

@if(session('success'))
<div class="mb-4 px-4 py-3 rounded-lg text-sm font-medium" style="background:rgba(16,185,129,0.15);border:1px solid rgba(16,185,129,0.3);color:#10b981;">
    <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
</div>
@endif
@if(session('error'))
<div class="mb-4 px-4 py-3 rounded-lg text-sm font-medium" style="background:rgba(239,68,68,0.15);border:1px solid rgba(239,68,68,0.3);color:#ef4444;">
    <i class="fas fa-times-circle mr-1"></i> {{ session('error') }}
</div>
@endif

<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-5">
    <div>
        <h2 class="text-[22px] font-bold tracking-[-0.3px]"><i class="fas fa-box text-accent mr-2"></i> Data Barang</h2>
        <p class="text-[13px] mt-1" style="color:var(--text-secondary);">Kelola master data barang inventaris MBG</p>
    </div>
    <a href="{{ route('barang.tambah') }}" class="btn-primary inline-flex items-center gap-2">
        <i class="fas fa-plus mr-1"></i> Tambah Barang
    </a>
</div>

{{-- Filter & Search --}}
<div class="section-card mb-4">
    <form method="GET" action="{{ route('barang.index') }}"
          class="flex flex-col sm:flex-row items-end gap-3 p-4">
        
        <div class="w-full sm:flex-1">
            <label class="block text-xs mb-1.5 font-medium" style="color:var(--text-secondary);">Cari Barang</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Kode atau nama barang..." class="form-input w-full">
        </div>
        
        <div class="w-full sm:w-auto">
            <label class="block text-xs mb-1.5 font-medium" style="color:var(--text-secondary);">Kategori</label>
            <select name="kategori" class="form-input w-full sm:w-auto">
                <option value="">Semua Kategori</option>
                @foreach($kategori as $kat)
                    <option value="{{ $kat }}" {{ request('kategori') == $kat ? 'selected' : '' }}>{{ $kat }}</option>
                @endforeach
            </select>
        </div>

        <div class="w-full sm:w-auto">
            <label class="block text-xs mb-1.5 font-medium" style="color:var(--text-secondary);">Status Stok</label>
            <select name="status" class="form-input w-full sm:w-auto">
                <option value="">Semua Status</option>
                <option value="aman" {{ request('status') == 'aman' ? 'selected' : '' }}>Stok Aman</option>
                <option value="menipis" {{ request('status') == 'menipis' ? 'selected' : '' }}>Stok Menipis</option>
                <option value="habis" {{ request('status') == 'habis' ? 'selected' : '' }}>Stok Habis</option>
            </select>
        </div>

        <button type="submit" class="btn-secondary px-4 py-2 text-sm w-full sm:w-auto"><i class="fas fa-search mr-1"></i> Filter</button>
        @if(request('search') || request('kategori') || request('status'))
        <a href="{{ route('barang.index') }}" class="btn-secondary px-4 py-2 text-sm w-full sm:w-auto text-center"><i class="fas fa-times mr-1"></i> Reset</a>
        @endif
    </form>
</div>

{{-- Tabel Data --}}
<div class="section-card">
    <div class="overflow-x-auto">
        <table class="data-table w-full min-w-[900px] lg:min-w-full">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Kode Barang</th>
                    <th>Nama Barang</th>
                    <th>Kategori</th>
                    <th>Stok</th>
                    <th>Min. Stok</th>
                    <th>Status</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @if($barang->isNotEmpty())
                @foreach($barang as $item)
                <tr>
                    <td class="mono">{{ $barang->firstItem() + $loop->index }}</td>
                    <td class="mono font-semibold">{{ $item->kode_barang }}</td>
                    <td>
                        <div class="font-medium text-gray-800">{{ $item->nama_barang }}</div>
                        @if($item->keterangan)
                            <div class="text-[10px] mt-0.5 text-gray-500 truncate max-w-[200px]" title="{{ $item->keterangan }}">
                                {{ $item->keterangan }}
                            </div>
                        @endif
                    </td>
                    <td>
                        <span class="px-2 py-0.5 rounded text-[10px] uppercase tracking-wider font-semibold border" style="background:var(--bg-color);border-color:var(--border-color);color:var(--text-secondary);">
                            {{ $item->kategori }}
                        </span>
                    </td>
                    <td class="mono font-bold text-base {{ $item->stok <= $item->stok_minimum ? 'text-danger' : 'text-emerald-600' }}">
                        {{ $item->stok }} <span class="text-xs font-normal text-gray-500">{{ $item->satuan }}</span>
                    </td>
                    <td class="mono text-gray-500">{{ $item->stok_minimum }}</td>
                    <td>
                        @if($item->stok == 0)
                            <span class="badge badge-red">Habis</span>
                        @elseif($item->stok <= $item->stok_minimum)
                            <span class="badge badge-warning">Menipis</span>
                        @else
                            <span class="badge badge-green">Aman</span>
                        @endif
                    </td>
                    <td class="text-center">
                        <div class="flex items-center justify-center gap-2">
                            <a href="{{ route('barang.edit', $item->id) }}" class="p-1.5 text-blue-500 hover:text-blue-700 transition" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('barang.destroy', $item->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus barang ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-1.5 text-red-500 hover:text-red-700 transition" title="Hapus">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
                @else
                <tr>
                    <td colspan="8" class="text-center py-16">
                        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-blue-50 mb-4">
                            <i class="fas fa-box-open text-2xl text-blue-500"></i>
                        </div>
                        <h3 class="text-base font-semibold text-gray-800 mb-1">Belum ada data barang</h3>
                        <p class="text-sm text-gray-500 mb-5 max-w-sm mx-auto">Mulai tambahkan master data barang ke dalam sistem untuk memantau stok dan transaksi inventaris Anda.</p>
                        <a href="{{ route('barang.tambah') }}" class="btn-primary inline-flex items-center gap-2">
                            <i class="fas fa-plus"></i> Tambah Barang Pertama
                        </a>
                    </td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($barang->hasPages())
    <div class="flex flex-col sm:flex-row items-center justify-between gap-3 px-5 py-3 text-xs"
         style="border-top:1px solid var(--border-color);color:var(--text-secondary);">
        <span>Menampilkan {{ $barang->firstItem() }}&ndash;{{ $barang->lastItem() }} dari {{ $barang->total() }} barang</span>
        {{ $barang->links('pagination::tailwind') }}
    </div>
    @endif
</div>
@endsection
