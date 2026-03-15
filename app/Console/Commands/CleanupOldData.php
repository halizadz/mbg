<?php

namespace App\Console\Commands;

use App\Models\BarangMasuk;
use App\Models\BarangKeluar;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class CleanupOldData extends Command
{
    protected $signature = 'app:cleanup-old-data
                            {--months=6 : Hapus data lebih lama dari N bulan}
                            {--disk-threshold=80 : Paksa cleanup jika disk usage melebihi persentase ini}
                            {--dry-run : Tampilkan apa yang akan dihapus tanpa menghapus}';

    protected $description = 'Hapus transaksi dan foto bukti lama untuk menghemat penyimpanan';

    public function handle(): int
    {
        $months        = (int) $this->option('months');
        $diskThreshold = (int) $this->option('disk-threshold');
        $dryRun        = $this->option('dry-run');
        $cutoffDate    = Carbon::now()->subMonths($months);

        $this->info("🗑️  Cleanup data lebih lama dari {$months} bulan (sebelum {$cutoffDate->format('d M Y')})");

        if ($dryRun) {
            $this->warn('⚠️  Mode DRY RUN — tidak ada data yang dihapus.');
        }

        // Cek disk usage
        $diskUsage = $this->getDiskUsagePercent();
        $this->info("📊 Disk usage saat ini: {$diskUsage}%");

        if ($diskUsage > $diskThreshold) {
            $this->warn("⚠️  Disk usage melebihi {$diskThreshold}% — cleanup diprioritaskan!");
        }

        // Cleanup Barang Masuk
        $masukCount = $this->cleanupTransaksi(
            BarangMasuk::class,
            'bukti/masuk',
            $cutoffDate,
            $dryRun,
            'Barang Masuk'
        );

        // Cleanup Barang Keluar
        $keluarCount = $this->cleanupTransaksi(
            BarangKeluar::class,
            'bukti/keluar',
            $cutoffDate,
            $dryRun,
            'Barang Keluar'
        );

        $total = $masukCount + $keluarCount;

        if ($total > 0) {
            $this->newLine();
            $action = $dryRun ? 'akan dihapus' : 'berhasil dihapus';
            $this->info("✅ Total: {$total} transaksi {$action}.");
        } else {
            $this->info('✅ Tidak ada data yang perlu dihapus.');
        }

        return self::SUCCESS;
    }

    /**
     * Hapus transaksi lama beserta foto buktinya.
     */
    private function cleanupTransaksi(
        string $modelClass,
        string $storagePath,
        Carbon $cutoffDate,
        bool $dryRun,
        string $label
    ): int {
        $query = $modelClass::where('tanggal', '<', $cutoffDate);
        $count = $query->count();

        if ($count === 0) {
            $this->line("  [{$label}] Tidak ada data lama.");
            return 0;
        }

        $this->line("  [{$label}] Ditemukan {$count} transaksi lama.");

        if (! $dryRun) {
            // Hapus foto bukti dulu
            $fotoPaths = $modelClass::where('tanggal', '<', $cutoffDate)
                ->whereNotNull('foto_bukti')
                ->pluck('foto_bukti');

            foreach ($fotoPaths as $path) {
                Storage::disk('public')->delete($path);
            }

            if ($fotoPaths->count() > 0) {
                $this->line("    🖼️  {$fotoPaths->count()} foto bukti dihapus.");
            }

            // Hapus record database
            $modelClass::where('tanggal', '<', $cutoffDate)->delete();
            $this->line("    🗃️  {$count} record dihapus.");
        }

        return $count;
    }

    /**
     * Dapatkan persentase penggunaan disk storage.
     */
    private function getDiskUsagePercent(): float
    {
        $storagePath = storage_path();
        $totalSpace  = @disk_total_space($storagePath);
        $freeSpace   = @disk_free_space($storagePath);

        if (! $totalSpace || $totalSpace === 0) {
            return 0;
        }

        return round(($totalSpace - $freeSpace) / $totalSpace * 100, 1);
    }
}
