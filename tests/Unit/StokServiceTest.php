<?php

namespace Tests\Unit;

use App\Models\Barang;
use App\Services\StokService;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StokServiceTest extends TestCase
{
    use RefreshDatabase;

    private StokService $stokService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->stokService = new StokService();
    }

    /** @test */
    public function tambah_stok_menambah_jumlah_dengan_benar(): void
    {
        $barang = Barang::create([
            'kode_barang'  => 'TST-001',
            'nama_barang'  => 'Barang Test',
            'kategori'     => 'Test',
            'satuan'       => 'pcs',
            'stok'         => 10,
            'stok_minimum' => 5,
        ]);

        $result = $this->stokService->tambahStok($barang->id, 5);

        $this->assertEquals(15, $result->stok);
        $this->assertEquals(15, $barang->fresh()->stok);
    }

    /** @test */
    public function kurangi_stok_mengurangi_jumlah_dengan_benar(): void
    {
        $barang = Barang::create([
            'kode_barang'  => 'TST-002',
            'nama_barang'  => 'Barang Test 2',
            'kategori'     => 'Test',
            'satuan'       => 'pcs',
            'stok'         => 20,
            'stok_minimum' => 5,
        ]);

        $result = $this->stokService->kurangiStok($barang->id, 8);

        $this->assertEquals(12, $result->stok);
        $this->assertEquals(12, $barang->fresh()->stok);
    }

    /** @test */
    public function kurangi_stok_gagal_jika_stok_tidak_cukup(): void
    {
        $barang = Barang::create([
            'kode_barang'  => 'TST-003',
            'nama_barang'  => 'Barang Test 3',
            'kategori'     => 'Test',
            'satuan'       => 'pcs',
            'stok'         => 3,
            'stok_minimum' => 5,
        ]);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Stok tidak cukup');

        $this->stokService->kurangiStok($barang->id, 10);
    }

    /** @test */
    public function kurangi_stok_tidak_mengubah_data_jika_gagal(): void
    {
        $barang = Barang::create([
            'kode_barang'  => 'TST-004',
            'nama_barang'  => 'Barang Test 4',
            'kategori'     => 'Test',
            'satuan'       => 'pcs',
            'stok'         => 5,
            'stok_minimum' => 5,
        ]);

        try {
            $this->stokService->kurangiStok($barang->id, 100);
        } catch (Exception $e) {
            // Expected
        }

        // Stok harus tetap 5, tidak berubah
        $this->assertEquals(5, $barang->fresh()->stok);
    }

    /** @test */
    public function tambah_stok_gagal_jika_barang_tidak_ditemukan(): void
    {
        $this->expectException(Exception::class);
        $this->stokService->tambahStok(999999, 10);
    }

    /** @test */
    public function cek_stok_mengembalikan_jumlah_stok(): void
    {
        $barang = Barang::create([
            'kode_barang'  => 'TST-005',
            'nama_barang'  => 'Barang Test 5',
            'kategori'     => 'Test',
            'satuan'       => 'pcs',
            'stok'         => 42,
            'stok_minimum' => 5,
        ]);

        $stok = $this->stokService->cekStok($barang->id);
        $this->assertEquals(42, $stok);
    }
}
