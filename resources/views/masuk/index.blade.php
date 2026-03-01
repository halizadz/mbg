@extends('layouts.app')

@section('title', 'Barang Masuk')
@section('breadcrumb', 'Beranda / Transaksi / Masuk')

@section('content')

{{-- Alert sukses / error --}}
@if(session('success'))
<div class="mb-4 px-4 py-3 rounded-lg text-sm font-medium" style="background:rgba(16,185,129,0.15);border:1px solid rgba(16,185,129,0.3);color:#10b981;">
    ✅ {{ session('success') }}
</div>
@endif
@if(session('error'))
<div class="mb-4 px-4 py-3 rounded-lg text-sm font-medium" style="background:rgba(239,68,68,0.15);border:1px solid rgba(239,68,68,0.3);color:#ef4444;">
    ❌ {{ session('error') }}
</div>
@endif

<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-5">
    <div>
        <h2 class="text-[22px] font-bold tracking-[-0.3px]">📥 Riwayat Barang Masuk</h2>
        <p class="text-[13px] mt-1" style="color:var(--text-secondary);">Semua transaksi penerimaan barang</p>
    </div>
    <a href="{{ route('transaksi.masuk.create') }}" class="btn-primary inline-flex items-center gap-2">
        ➕ Tambah Transaksi
    </a>
</div>

{{-- Filter --}}
<div class="section-card mb-4">
    <form method="GET" action="{{ route('transaksi.masuk') }}"
          class="flex flex-col sm:flex-row items-end gap-3 p-4">
        <div class="w-full sm:w-auto">
            <label class="block text-xs mb-1.5 font-medium" style="color:var(--text-secondary);">Dari</label>
            <input type="date" name="dari" value="{{ request('dari') }}" class="form-input w-full sm:w-auto">
        </div>
        <div class="w-full sm:w-auto">
            <label class="block text-xs mb-1.5 font-medium" style="color:var(--text-secondary);">Sampai</label>
            <input type="date" name="sampai" value="{{ request('sampai') }}" class="form-input w-full sm:w-auto">
        </div>
        <button type="submit" class="btn-secondary px-4 py-2 text-sm w-full sm:w-auto">🔍 Filter</button>
        @if(request('dari') || request('sampai'))
        <a href="{{ route('transaksi.masuk') }}" class="btn-secondary px-4 py-2 text-sm w-full sm:w-auto text-center">✕ Reset</a>
        @endif
    </form>
</div>

{{-- Tabel --}}
<div class="section-card">
    <div class="overflow-x-auto">
        <table class="data-table w-full min-w-[700px] lg:min-w-full">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Tanggal</th>
                    <th>Kode</th>
                    <th>Nama Barang</th>
                    <th>Jumlah</th>
                    <th>Supplier</th>
                    <th>Keterangan</th>
                    <th>Dicatat oleh</th>
                </tr>
            </thead>
            <tbody>
                @forelse($riwayat as $item)
                <tr>
                    <td class="mono">{{ $riwayat->firstItem() + $loop->index }}</td>
                    <td>{{ $item->tanggal->format('d M Y') }}</td>
                    <td class="mono">{{ $item->barang->kode ?? '-' }}</td>
                    <td>{{ $item->barang->nama ?? '-' }}</td>
                    <td class="mono font-bold" style="color:#10b981;">+{{ $item->jumlah }}</td>
                    <td>{{ $item->supplier ?? '-' }}</td>
                    <td class="text-xs" style="color:var(--text-secondary);">{{ $item->keterangan ?? '-' }}</td>
                    <td>{{ $item->user->name ?? 'Admin' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-8" style="color:var(--text-secondary);">
                        Belum ada transaksi masuk.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($riwayat->hasPages())
    <div class="flex flex-col sm:flex-row items-center justify-between gap-3 px-5 py-3 text-xs"
         style="border-top:1px solid var(--border-color);color:var(--text-secondary);">
        <span>Menampilkan {{ $riwayat->firstItem() }}–{{ $riwayat->lastItem() }} dari {{ $riwayat->total() }} transaksi</span>
        {{ $riwayat->links() }}
    </div>
    @endif
</div>
@endsection