@extends('layouts.app')

@section('title', 'Laporan')
@section('breadcrumb', 'Beranda / Laporan')

@push('styles')
<style>
@media print {
    aside, header, .btn-secondary, .btn-primary, form, .pagination { display: none !important; }
    main { margin: 0 !important; }
    .section-card { box-shadow: none !important; border: 1px solid #e2e8f0 !important; }
    body { background: #fff !important; color: #000 !important; font-size: 12px !important; }
    .print-header { display: block !important; }
    table { font-size: 11px !important; }
}
.print-header { display: none; }
</style>
@endpush

@section('content')
<div class="wireframe-note mb-5">
    <i class="fas fa-info-circle text-blue-500 mr-1"></i> <strong>Laporan:</strong> Filter tanggal, riwayat transaksi lengkap, dan tombol print/export PDF.
</div>

<div class="mb-5">
    <h2 class="text-[22px] font-bold tracking-[-0.3px]"><i class="fas fa-chart-bar text-indigo-400 mr-2"></i> Laporan Transaksi</h2>
    <p class="text-[13px] mt-1" style="color:var(--text-secondary);">Riwayat semua transaksi dengan filter tanggal</p>
</div>

<div class="section-card">
    {{-- Toolbar --}}
    <form action="{{ route('laporan.index') }}" method="GET" class="px-4 sm:px-5 py-3.5 flex flex-col lg:flex-row items-start lg:items-center gap-3" style="border-bottom:1px solid var(--border-color);">
        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-3 w-full lg:w-auto">
            <div class="flex items-center gap-2 w-full sm:w-auto">
                <label class="text-xs font-medium whitespace-nowrap" style="color:var(--text-secondary);">Dari:</label>
                <input type="date" name="start_date" value="{{ $startDate }}" class="form-input text-xs sm:text-[13px] w-full sm:w-auto">
            </div>
            <div class="flex items-center gap-2 w-full sm:w-auto">
                <label class="text-xs font-medium whitespace-nowrap" style="color:var(--text-secondary);">Sampai:</label>
                <input type="date" name="end_date" value="{{ $endDate }}" class="form-input text-xs sm:text-[13px] w-full sm:w-auto">
            </div>
        </div>

        <div class="flex flex-wrap items-center gap-2 w-full lg:w-auto lg:ml-auto">
            <select name="type" class="form-input text-xs sm:text-[13px] w-full sm:w-auto" onchange="this.form.submit()">
                <option value="all" {{ $type == 'all' ? 'selected' : '' }}>Semua Tipe</option>
                <option value="masuk" {{ $type == 'masuk' ? 'selected' : '' }}>Barang Masuk</option>
                <option value="keluar" {{ $type == 'keluar' ? 'selected' : '' }}>Barang Keluar</option>
            </select>
            <button type="submit" class="btn-secondary px-4 py-2 text-xs sm:text-[13px]"><i class="fas fa-search mr-1"></i> Filter</button>
            <a href="{{ route('laporan.print', ['start_date' => $startDate, 'end_date' => $endDate, 'type' => $type]) }}"
               class="btn-secondary px-4 py-2 text-xs sm:text-[13px] inline-flex items-center gap-1"
               target="_blank"><i class="fas fa-print mr-1"></i> Print</a>
            <a href="{{ route('laporan.export-pdf', ['start_date' => $startDate, 'end_date' => $endDate, 'type' => $type]) }}"
               class="btn-primary px-4 py-2 text-xs sm:text-[13px] inline-flex items-center gap-1"
               target="_blank"><i class="fas fa-file-pdf mr-1"></i> Export PDF</a>
        </div>
    </form>

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
                    <th>Stok Saat Ini</th>
                    <th>Bukti</th>
                    <th>Keterangan</th>
                    <th>Admin</th>
                </tr>
            </thead>
            <tbody>
                @if($transaksi->count() > 0)
                    @foreach($transaksi as $item)
                    <tr>
                        <td>{{ $item['tanggal'] }}</td>
                        <td class="mono">{{ $item['kode'] }}</td>
                        <td>{{ $item['nama'] }}</td>
                        <td>
                            <span class="badge badge-{{ $item['tipeColor'] }}">
                                {!! $item['tipe'] === 'Masuk' ? '<i class="fas fa-arrow-down mr-1"></i>' : '<i class="fas fa-arrow-up mr-1"></i>' !!} {{ $item['tipe'] }}
                            </span>
                        </td>
                        <td class="mono">{{ $item['jml'] }}</td>
                        <td class="mono {{ $item['stok'] <= 3 ? 'text-danger' : '' }}">{{ $item['stok'] }}</td>
                        <td class="text-center">
                            @if($item['foto_bukti'])
                                <a href="{{ asset('storage/' . $item['foto_bukti']) }}" target="_blank">
                                    <img src="{{ asset('storage/' . $item['foto_bukti']) }}" alt="Bukti" class="w-10 h-10 object-cover rounded mx-auto border" style="border-color:var(--border-color);">
                                </a>
                            @else
                                <span class="text-[11px]" style="color:var(--text-secondary);">-</span>
                            @endif
                        </td>
                        <td class="text-[12px]" style="color:var(--text-secondary);">{{ $item['ket'] }}</td>
                        <td>{{ $item['admin'] }}</td>
                    </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="9" class="text-center py-16">
                            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-indigo-50 mb-4">
                                <i class="fas fa-file-invoice text-2xl text-indigo-500"></i>
                            </div>
                            <h3 class="text-base font-semibold text-gray-800 mb-1">Tidak ada data transaksi</h3>
                            <p class="text-sm text-gray-500 max-w-sm mx-auto">Belum ada transaksi pada rentang tanggal yang dipilih atau sistem belum memiliki data.</p>
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="px-5 py-4" style="border-top:1px solid var(--border-color);">
        {{ $transaksi->links('pagination::tailwind') }}
    </div>
</div>
@endsection