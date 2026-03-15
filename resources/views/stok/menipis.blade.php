@extends('layouts.app')

@section('title', 'Stok Menipis')
@section('breadcrumb', 'Beranda / Laporan / Stok Menipis')

@section('content')
<div class="wireframe-note mb-5">
    <i class="fas fa-info-circle text-blue-500 mr-1"></i> <strong>Stok Menipis:</strong> Daftar semua barang dengan stok di bawah atau mendekati batas minimum.
</div>

<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-5">
    <div>
        <h2 class="text-[22px] font-bold tracking-[-0.3px]"><i class="fas fa-exclamation-triangle text-warning mr-2"></i> Stok Menipis</h2>
        <p class="text-[13px] mt-1" style="color:var(--text-secondary);">Barang yang membutuhkan restock segera</p>
    </div>
    <a href="{{ route('transaksi.masuk.create') }}" class="btn-primary inline-flex items-center gap-2">
        <i class="fas fa-inbox mr-1"></i> Input Barang Masuk
    </a>
</div>

<div class="section-card">
    <div class="overflow-x-auto">
        <table class="data-table w-full min-w-[750px] lg:min-w-full">
            <thead>
                <tr>
                    <th class="w-[12%]">Status</th>
                    <th class="w-[15%]">Kode Barang</th>
                    <th>Nama Barang</th>
                    <th class="w-[15%]">Kategori</th>
                    <th class="w-[10%] text-right">Stok</th>
                    <th class="w-[10%] text-right">Min. Stok</th>
                    <th class="w-[10%] text-right">Kekurangan</th>
                    <th class="w-[10%] text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @if($barang->count() > 0)
                    @foreach($barang as $item)
                    <tr>
                        <td>
                            @if($item->warnaStatus() === 'red')
                                <span class="badge badge-red"><i class="fas fa-exclamation-circle mr-1"></i>Kritis</span>
                            @else
                                <span class="badge badge-warning"><i class="fas fa-exclamation-triangle mr-1"></i>Menipis</span>
                            @endif
                        </td>
                        <td class="mono font-semibold">{{ $item->kode_barang }}</td>
                        <td>
                            <div class="font-medium text-gray-800">{{ $item->nama_barang }}</div>
                        </td>
                        <td>
                            <span class="px-2 py-0.5 rounded text-[10px] uppercase tracking-wider font-semibold border" style="background:var(--bg-color);border-color:var(--border-color);color:var(--text-secondary);">
                                {{ $item->kategori }}
                            </span>
                        </td>
                        <td class="mono font-bold text-base text-danger text-right">
                            {{ $item->stok }} <span class="text-xs font-normal text-gray-500">{{ $item->satuan }}</span>
                        </td>
                        <td class="mono text-gray-500 text-right">{{ $item->stok_minimum }}</td>
                        <td class="mono text-danger font-semibold text-right">{{ abs($item->stok - $item->stok_minimum) }}</td>
                        <td class="text-center">
                            <a href="{{ route('transaksi.masuk.create', ['barang_id' => $item->id]) }}"
                               class="btn-primary text-[11px] px-3 py-1.5 inline-flex items-center justify-center gap-1 w-full" title="Input Barang Masuk">
                                <i class="fas fa-inbox"></i> Restock
                            </a>
                        </td>
                    </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="8" class="text-center py-16">
                            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-emerald-50 mb-4 border border-emerald-100 shadow-sm">
                                <i class="fas fa-shield-alt text-2xl text-emerald-500"></i>
                            </div>
                            <h3 class="text-base font-semibold text-gray-800 mb-1">Semua Stok Aman</h3>
                            <p class="text-sm text-gray-500 mb-5 max-w-sm mx-auto">Tidak ada barang yang mendekati batas minimum stok saat ini. Inventaris Anda terkendali dengan baik!</p>
                            <a href="{{ route('barang.index') }}" class="btn-secondary text-sm inline-flex items-center gap-2">
                                <i class="fas fa-box"></i> Lihat Data Barang
                            </a>
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

    @if($barang->hasPages())
    <div class="flex flex-col sm:flex-row items-center justify-between gap-3 px-5 py-3 text-xs"
         style="border-top:1px solid var(--border-color);color:var(--text-secondary);">
        <span>Menampilkan {{ $barang->firstItem() }}&ndash;{{ $barang->lastItem() }} dari {{ $barang->total() }} barang</span>
        {{ $barang->links('pagination::tailwind') }}
    </div>
    @endif
</div>
@endsection