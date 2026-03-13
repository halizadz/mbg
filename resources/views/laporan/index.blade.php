@extends('layouts.app')

@section('title', 'Laporan')
@section('breadcrumb', 'Beranda / Laporan')

@section('content')
<div class="wireframe-note mb-5">
    🔵 <strong>Laporan:</strong> Filter tanggal, riwayat transaksi lengkap, dan tombol print/export PDF.
</div>

<div class="mb-5">
    <h2 class="text-[22px] font-bold tracking-[-0.3px]">📊 Laporan Transaksi</h2>
    <p class="text-[13px] mt-1" style="color:var(--text-secondary);">Riwayat semua transaksi dengan filter tanggal</p>
</div>

<div class="section-card">
    {{-- Toolbar --}}
    <div class="px-4 sm:px-5 py-3.5 flex flex-col lg:flex-row items-start lg:items-center gap-3" style="border-bottom:1px solid var(--border-color);">
        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-3 w-full lg:w-auto">
            <div class="flex items-center gap-2 w-full sm:w-auto">
                <label class="text-xs font-medium whitespace-nowrap" style="color:var(--text-secondary);">Dari:</label>
                <input type="date" value="2026-02-01" class="form-input text-xs sm:text-[13px] w-full sm:w-auto">
            </div>
            <div class="flex items-center gap-2 w-full sm:w-auto">
                <label class="text-xs font-medium whitespace-nowrap" style="color:var(--text-secondary);">Sampai:</label>
                <input type="date" value="2026-02-21" class="form-input text-xs sm:text-[13px] w-full sm:w-auto">
            </div>
        </div>

        <div class="flex flex-wrap items-center gap-2 w-full lg:w-auto lg:ml-auto">
            <select class="form-input text-xs sm:text-[13px] w-full sm:w-auto">
                <option>Semua Tipe</option>
                <option>Barang Masuk</option>
                <option>Barang Keluar</option>
            </select>
            <button class="btn-secondary px-4 py-2 text-xs sm:text-[13px]">🔍 Filter</button>
            <button class="btn-secondary px-4 py-2 text-xs sm:text-[13px]">🖨️ Print</button>
            <button class="btn-primary px-4 py-2 text-xs sm:text-[13px]">📄 Export PDF</button>
        </div>
    </div>

    {{-- Table --}}
    <div class="overflow-x-auto">
        <table class="data-table w-full min-w-[900px] lg:min-w-full">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Kode</th>
                    <th>Nama Barang</th>
                    <th>Tipe</th>
                    <th>Jumlah</th>
                    <th>Stok Sesudah</th>
                    <th>Keterangan</th>
                    <th>Admin</th>
                </tr>
            </thead>
            <tbody>
                @php
                $transaksi = [
                    ['17 Feb 2026', 'BRG-012', 'Kertas HVS A4',       'Keluar', 'red',   10,  2, 'Dept. Keuangan',  'Admin'],
                    ['17 Feb 2026', 'BRG-005', 'Ballpoint Pilot G2',  'Masuk',  'green', 50,120, 'Restock bulanan', 'Admin'],
                    ['16 Feb 2026', 'BRG-021', 'Binder Clip No.2',    'Masuk',  'green', 30, 85, 'PO: 2026-02-003', 'Admin'],
                    ['16 Feb 2026', 'BRG-031', 'Tinta Printer Hitam', 'Keluar', 'red',    5,  1, 'Dept. IT',        'Admin'],
                    ['15 Feb 2026', 'BRG-014', 'Map Plastik Bening',  'Masuk',  'green', 24, 67, 'Supplier: Gramedia','Admin'],
                ];
                @endphp
                @foreach($transaksi as [$tgl, $kode, $nama, $tipe, $tipeColor, $jml, $stok, $ket, $admin])
                <tr>
                    <td>{{ $tgl }}</td>
                    <td class="mono">{{ $kode }}</td>
                    <td>{{ $nama }}</td>
                    <td><span class="badge badge-{{ $tipeColor }}">{{ $tipe === 'Masuk' ? '📥' : '📤' }} {{ $tipe }}</span></td>
                    <td class="mono">{{ $jml }}</td>
                    <td class="mono {{ $stok <= 3 ? 'text-danger' : '' }}">{{ $stok }}</td>
                    <td class="text-[12px]" style="color:var(--text-secondary);">{{ $ket }}</td>
                    <td>{{ $admin }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="flex flex-col sm:flex-row items-center justify-between gap-3 px-5 py-3 text-xs" style="border-top:1px solid var(--border-color);color:var(--text-secondary);">
        <span>Menampilkan 1–5 dari 128 transaksi</span>
        <div class="flex gap-1">
            @foreach(['‹', '1', '2', '3', '···', '26', '›'] as $i => $p)
            <button class="w-7 h-7 rounded-md text-xs {{ $p == '1' ? 'bg-accent text-white border-accent' : '' }}"
                style="<? $p != '1' ? 'border:1px solid var(--border-color);background:var(--bg-secondary);color:var(--text-primary);' : '' ?>">
                {{ $p }}
            </button>
            @endforeach
        </div>
    </div>
</div>
@endsection