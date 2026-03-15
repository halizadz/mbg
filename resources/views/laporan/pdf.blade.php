<!DOCTYPE html>
<html lang="id">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Laporan Transaksi &mdash; InvenTrack</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 11px; color: #1e293b; background: #ffffff; padding: 20px; }

        /* Header Layout using Table */
        .layout-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .layout-table td { vertical-align: top; }
        
        .header-bg { background-color: #1e3a5f; color: #ffffff; padding: 20px; }
        .company-name { font-size: 22px; font-weight: bold; }
        .company-sub { font-size: 11px; margin-top: 5px; color: #cbd5e1; }
        
        .report-title { font-size: 14px; font-weight: bold; text-align: right; }
        .report-meta { font-size: 11px; text-align: right; margin-top: 5px; color: #cbd5e1; }

        /* Info Bar using Table */
        .info-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; background-color: #f8fafc; border: 1px solid #e2e8f0; }
        .info-table td { padding: 12px 15px; border-right: 1px solid #e2e8f0; width: 25%; }
        .info-table td:last-child { border-right: none; }
        
        .info-label { font-size: 9px; font-weight: bold; text-transform: uppercase; color: #64748b; margin-bottom: 4px; display: block; }
        .info-value { font-size: 12px; font-weight: bold; color: #0f172a; }

        /* Data Table */
        .data-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .data-table thead th { background-color: #1e3a5f; color: #ffffff; padding: 10px; text-align: left; font-size: 10px; font-weight: bold; text-transform: uppercase; border: 1px solid #1e3a5f; }
        .data-table tbody td { padding: 8px 10px; border: 1px solid #e2e8f0; font-size: 10px; vertical-align: middle; }
        
        .data-table tbody tr:nth-child(even) { background-color: #f8fafc; }
        
        .center { text-align: center; }
        .right { text-align: right; }
        .mono { font-family: 'Courier New', Courier, monospace; }

        /* Status Colors */
        .text-masuk { color: #15803d; font-weight: bold; }
        .text-keluar { color: #b91c1c; font-weight: bold; }
        .stok-low { color: #b91c1c; font-weight: bold; }

        /* Badge Simulation */
        .badge { padding: 3px 6px; font-size: 9px; font-weight: bold; border-radius: 3px; border: 1px solid #ccc; display: inline-block; }
        .badge-masuk { background-color: #dcfce7; color: #166534; border-color: #bbf7d0; }
        .badge-keluar { background-color: #fee2e2; color: #991b1b; border-color: #fecaca; }

        /* Footer */
        .footer { margin-top: 30px; border-top: 1px solid #e2e8f0; padding-top: 10px; font-size: 9px; color: #64748b; }
        .footer table { width: 100%; border-collapse: collapse; }
        .footer td { vertical-align: top; }
        .footer .right { text-align: right; }
    </style>
</head>
<body>

    {{-- Header --}}
    <table class="layout-table header-bg">
        <tr>
            <td style="width: 50%;">
                <div class="company-name">InvenTrack</div>
                <div class="company-sub">Sistem Manajemen Inventaris MBG</div>
            </td>
            <td style="width: 50%;">
                <div class="report-title">LAPORAN TRANSAKSI &mdash; {{ strtoupper($typeLabel) }}</div>
                <div class="report-meta">Periode: {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}</div>
                <div class="report-meta">Dicetak: {{ $generatedAt }}</div>
            </td>
        </tr>
    </table>

    {{-- Info Bar --}}
    <table class="info-table">
        <tr>
            <td>
                <span class="info-label">Total Transaksi</span>
                <span class="info-value">{{ $transaksi->count() }} data</span>
            </td>
            <td>
                <span class="info-label">Total Masuk</span>
                <span class="info-value text-masuk">+{{ $transaksi->where('tipe','Masuk')->sum('jml') }}</span>
            </td>
            <td>
                <span class="info-label">Total Keluar</span>
                <span class="info-value text-keluar">-{{ $transaksi->where('tipe','Keluar')->sum('jml') }}</span>
            </td>
            <td>
                <span class="info-label">Tipe Filter</span>
                <span class="info-value">{{ $typeLabel }}</span>
            </td>
        </tr>
    </table>

    {{-- Data Table --}}
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 3%; text-align: center;">No</th>
                <th style="width: 10%;">Tanggal</th>
                <th style="width: 10%;">Kode</th>
                <th style="width: 18%;">Nama Barang</th>
                <th style="width: 7%; text-align: center;">Tipe</th>
                <th style="width: 9%; text-align: right;">Jumlah</th>
                <th style="width: 9%; text-align: right;">Sisa Stok</th>
                <th style="width: 10%; text-align: center;">Bukti</th>
                <th style="width: 15%;">Keterangan</th>
                <th style="width: 9%;">Admin</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($transaksi as $item)
            <tr>
                <td class="center mono" style="color: #64748b;">{{ $loop->iteration }}</td>
                <td class="mono">{{ $item['tanggal'] }}</td>
                <td class="mono">{{ $item['kode'] }}</td>
                <td>{{ $item['nama'] }}</td>
                <td class="center">
                    @if($item['tipe'] === 'Masuk')
                        <span class="badge badge-masuk">Masuk</span>
                    @else
                        <span class="badge badge-keluar">Keluar</span>
                    @endif
                </td>
                <td class="right mono {{ $item['tipe'] === 'Masuk' ? 'text-masuk' : 'text-keluar' }}">
                    {{ $item['tipe'] === 'Masuk' ? '+' : '-' }}{{ $item['jml'] }}
                </td>
                <td class="right mono {{ $item['stok'] <= 3 ? 'stok-low' : '' }}">{{ $item['stok'] }}</td>
                <td class="center">
                    @if($item['foto_bukti'])
                        @if(isset($isPrint) && $isPrint)
                            <!-- For HTML Print View -->
                            <img src="{{ asset('storage/' . $item['foto_bukti']) }}" style="max-height: 40px; border-radius: 4px;">
                        @else
                            <!-- For DomPDF (needs absolute/local path depending on env) -->
                            <img src="{{ public_path('storage/' . $item['foto_bukti']) }}" style="max-height: 40px; border-radius: 4px;">
                        @endif
                    @else
                        -
                    @endif
                </td>
                <td>{{ \Illuminate\Support\Str::limit($item['ket'], 30) }}</td>
                <td>{{ $item['admin'] }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="10" style="text-align:center; padding: 25px; color: #64748b;">
                    Tidak ada data transaksi pada periode ini.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Footer --}}
    <div class="footer">
        <table>
            <tr>
                <td>InvenTrack &mdash; Sistem Manajemen Inventaris MBG</td>
                <td class="right">Laporan di-generate otomatis oleh sistem pada {{ $generatedAt }}</td>
            </tr>
        </table>
    </div>

    {{-- Auto Print Script (Jika dibuka sebagai halaman web biasa) --}}
    @if(isset($isPrint) && $isPrint)
    <script>
        window.onload = function() {
            window.print();
        }
    </script>
    @endif

</body>
</html>
