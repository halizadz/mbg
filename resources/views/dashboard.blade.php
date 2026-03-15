@extends('layouts.app')

@section('title', 'Dashboard')
@section('breadcrumb', 'Beranda / Dashboard')

@section('content')

<!-- Stats Grid -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 mb-4 sm:mb-6">
    @include('components.stat-card', [
        'color' => 'blue', 'label' => 'Total Stok Tersedia',
        'icon' => '<i class="fas fa-box"></i>', 'value' => number_format($totalStok),
        'sub' => $totalJenisBarang . ' jenis barang',
        'trend' => 'up', 'trendText' => ''
    ])
    @include('components.stat-card', [
        'color' => 'green', 'label' => 'Barang Masuk Hari Ini',
        'icon' => '<i class="fas fa-arrow-down"></i>', 'value' => number_format($masukHariIni),
        'sub' => $txMasukHariIni . ' transaksi',
        'trend' => $diffMasuk >= 0 ? 'up' : 'down',
        'trendText' => ($diffMasuk >= 0 ? '<i class="fas fa-arrow-up mr-1"></i> ' : '<i class="fas fa-arrow-down mr-1"></i> ') . abs($diffMasuk) . ' vs kemarin'
    ])
    @include('components.stat-card', [
        'color' => 'orange', 'label' => 'Barang Keluar Hari Ini',
        'icon' => '<i class="fas fa-arrow-up"></i>', 'value' => number_format($keluarHariIni),
        'sub' => $txKeluarHariIni . ' transaksi',
        'trend' => $diffKeluar <= 0 ? 'down' : 'up',
        'trendText' => ($diffKeluar >= 0 ? '<i class="fas fa-arrow-up mr-1"></i> ' : '<i class="fas fa-arrow-down mr-1"></i> ') . abs($diffKeluar) . ' vs kemarin'
    ])
    @include('components.stat-card', [
        'color' => 'red', 'label' => 'Stok Menipis',
        'icon' => '<i class="fas fa-exclamation-triangle"></i>', 'value' => $stokMenipis,
        'sub' => $stokMenipis > 0 ? 'Perlu restock segera' : 'Semua stok aman',
        'trend' => $stokMenipis > 0 ? 'down' : 'up',
        'trendText' => $stokMenipis > 0 ? 'Kritis' : 'Aman'
    ])
</div>

<!-- Chart & Alerts -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-4 sm:mb-6">
    <!-- Chart -->
    <div class="lg:col-span-2">
        <div class="section-card">
            <div class="section-head px-4 sm:px-5 py-3 sm:py-4">
                <div>
                    <div class="section-title">Grafik Transaksi &mdash; 7 Hari Terakhir</div>
                    <div class="section-sub">Perbandingan barang masuk vs keluar</div>
                </div>
                <a href="{{ route('laporan.index') }}" class="section-action text-[11px] sm:text-xs px-2 sm:px-2.5 py-1 sm:py-1.5">
                    Lihat Laporan &rarr;
                </a>
            </div>
            <div class="p-3 sm:p-5">
                <div class="overflow-x-auto pb-2">
                    @php
                        $maxVal = max(1, max(array_merge($chartMasuk, $chartKeluar)));
                        $chartH = 140;
                    @endphp
                    <div class="flex items-end gap-1 sm:gap-2 min-w-[560px] sm:min-w-0" style="height:{{ $chartH + 20 }}px;">
                        @foreach($chartDays as $index => $day)
                        @php
                            $hMasuk  = (int) round($chartMasuk[$index]  / $maxVal * $chartH);
                            $hKeluar = (int) round($chartKeluar[$index] / $maxVal * $chartH);
                            $isToday = $index === count($chartDays) - 1;
                        @endphp
                        <div class="flex-1 flex flex-col items-center gap-1">
                            <div class="flex gap-[3px] items-end w-full" style="height:{{ $chartH }}px;">
                                {{-- Bar Masuk --}}
                                <div class="flex-1 rounded-t transition-opacity hover:opacity-75 cursor-pointer"
                                     style="height:{{ max(2, $hMasuk) }}px; background:linear-gradient(to bottom,#3b82f6,rgba(59,130,246,0.5));"
                                     title="Masuk: {{ $chartMasuk[$index] }}"></div>
                                {{-- Bar Keluar --}}
                                <div class="flex-1 rounded-t transition-opacity hover:opacity-75 cursor-pointer"
                                     style="height:{{ max(2, $hKeluar) }}px; background:linear-gradient(to bottom,#ef4444,rgba(239,68,68,0.5));"
                                     title="Keluar: {{ $chartKeluar[$index] }}"></div>
                            </div>
                            <span class="text-[10px]" style="color:{{ $isToday ? '#3b82f6' : 'var(--text-secondary)' }}; font-weight:{{ $isToday ? '600' : '400' }};">
                                {{ $day }}
                            </span>
                        </div>
                        @endforeach
                    </div>
                </div>
                <!-- Legend -->
                <div class="flex gap-3 sm:gap-4 mt-3 pt-3 text-[11px] sm:text-xs" style="border-top:1px solid var(--border-color);">
                    <span class="flex items-center gap-1.5">
                        <span class="inline-block w-2 h-2 rounded-sm" style="background:#3b82f6;"></span>
                        Barang Masuk
                    </span>
                    <span class="flex items-center gap-1.5">
                        <span class="inline-block w-2 h-2 rounded-sm" style="background:#ef4444;"></span>
                        Barang Keluar
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Alerts & Actions -->
    <div class="space-y-4">
        <!-- Alert Stok -->
        <div class="section-card">
            <div class="section-head px-4 sm:px-5 py-3 sm:py-4">
                <div>
                    <div class="section-title"><i class="fas fa-exclamation-triangle text-warning mr-2"></i> Stok Menipis</div>
                    <div class="section-sub">Di bawah batas minimum</div>
                </div>
                <a href="{{ route('stok.menipis') }}" class="section-action text-[11px] sm:text-xs">Lihat Semua</a>
            </div>
            <div class="divide-y" style="border-color:var(--border-color);">
                @if($alertStok->isNotEmpty())
                @foreach($alertStok as $item)
                    @include('components.alert-item', [
                        'name'  => $item->nama_barang,
                        'code'  => $item->kode_barang,
                        'unit'  => $item->satuan,
                        'stok'  => $item->stok,
                        'color' => $item->stok == 0 ? 'danger' : 'warning',
                    ])
                @endforeach
                @else
                    <div class="px-4 py-6 text-center text-xs" style="color:var(--text-secondary);">
                        <i class="fas fa-check-circle text-emerald-500 mr-1"></i> Semua stok dalam kondisi aman.
                    </div>
                @endif
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="section-card">
            <div class="section-head px-4 sm:px-5 py-3 sm:py-4">
                <div class="section-title">Aksi Cepat</div>
            </div>
            @include('components.quick-actions')
        </div>
    </div>
</div>

<!-- Riwayat Transaksi -->
<div class="section-card overflow-hidden">
    <div class="section-head px-4 sm:px-5 py-3 sm:py-4">
        <div>
            <div class="section-title">Transaksi Terbaru</div>
            <div class="section-sub">10 transaksi terakhir hari ini</div>
        </div>
        <a href="{{ route('laporan.index') }}" class="section-action text-[11px] sm:text-xs">Lihat Semua &rarr;</a>
    </div>

    <div class="overflow-x-auto">
        <table class="data-table w-full min-w-[700px] sm:min-w-full">
            <thead>
                <tr>
                    <th>Waktu</th>
                    <th>Kode Barang</th>
                    <th>Nama Barang</th>
                    <th>Tipe</th>
                    <th>Jumlah</th>
                    <th>Stok Sekarang</th>
                    <th>Dicatat oleh</th>
                </tr>
            </thead>
            <tbody>
                @if(count($transaksiTerbaru) > 0)
                @foreach($transaksiTerbaru as $row)
                <tr>
                    <td>{{ $row['waktu'] }}</td>
                    <td class="mono">{{ $row['kode'] }}</td>
                    <td>{{ $row['nama'] }}</td>
                    <td>
                        @if($row['tipe'] === 'Masuk')
                            <span class="badge badge-green"><i class="fas fa-arrow-down mr-1"></i> Masuk</span>
                        @else
                            <span class="badge badge-red"><i class="fas fa-arrow-up mr-1"></i> Keluar</span>
                        @endif
                    </td>
                    <td class="mono">{{ $row['jumlah'] }}</td>
                    <td class="mono {{ $row['stok'] <= 3 ? 'text-danger' : '' }}">{{ $row['stok'] }}</td>
                    <td>{{ $row['admin'] }}</td>
                </tr>
                @endforeach
                @else
                <tr>
                    <td colspan="7" class="text-center py-8" style="color:var(--text-secondary);">
                        Belum ada transaksi hari ini.
                    </td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>
@endsection