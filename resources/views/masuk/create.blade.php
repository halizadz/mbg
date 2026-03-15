@extends('layouts.app')

@section('title', 'Tambah Barang Masuk')
@section('breadcrumb', 'Beranda / Transaksi / Masuk / Tambah')

@section('content')

<div class="flex items-center justify-between mb-5">
    <div>
        <h2 class="text-[22px] font-bold tracking-[-0.3px]"><i class="fas fa-arrow-down text-success mr-2"></i> Tambah Barang Masuk</h2>
        <p class="text-[13px] mt-1" style="color:var(--text-secondary);">Catat penerimaan barang ke gudang</p>
    </div>
    <a href="{{ route('transaksi.masuk') }}" class="btn-secondary text-sm px-4 py-2">&larr; Kembali</a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
    {{-- Form --}}
    <div class="section-card">
        <div class="section-head px-4 sm:px-5 py-3 sm:py-4">
            <div class="section-title">Form Barang Masuk</div>
        </div>

        <div class="p-4 sm:p-5">
            @if($errors->any())
            <div class="mb-4 px-4 py-3 rounded-lg text-sm" style="background:rgba(239,68,68,0.15);border:1px solid rgba(239,68,68,0.3);color:#ef4444;">
                <ul class="list-disc list-inside space-y-1">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form id="formMasuk" action="{{ route('transaksi.masuk.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf

                {{-- Pilih Barang --}}
                <div>
                    <label class="block text-xs font-medium mb-1.5" style="color:var(--text-secondary);">
                        Pilih Barang <span style="color:#ef4444;">*</span>
                    </label>
                    <select name="barang_id" id="barang_id" class="form-input w-full" required>
                        <option value="">-- Pilih Barang --</option>
                        @foreach($barang as $b)
                        <option value="{{ $b->id }}"
                            data-stok="{{ $b->stok }}"
                            data-satuan="{{ $b->satuan }}"
                            {{ old('barang_id') == $b->id ? 'selected' : '' }}>
                            {{ $b->kode_barang }} &mdash; {{ $b->nama_barang }} (Stok: {{ $b->stok }} {{ $b->satuan }})
                        </option>
                        @endforeach
                    </select>
                </div>

                {{-- Info Stok --}}
                <div id="stokInfo" class="rounded-lg p-3 text-xs hidden"
                     style="background:rgba(16,185,129,0.08);border:1px solid rgba(16,185,129,0.25);color:#10b981;">
                    <i class="fas fa-box text-emerald-500 mr-1"></i> Stok saat ini: <strong class="font-mono" id="stokNow">-</strong>
                </div>

                {{-- Jumlah --}}
                <div>
                    <label class="block text-xs font-medium mb-1.5" style="color:var(--text-secondary);">
                        Jumlah Masuk <span style="color:#ef4444;">*</span>
                    </label>
                    <input type="number" name="jumlah" id="jumlah" min="1"
                           value="{{ old('jumlah') }}" placeholder="0"
                           class="form-input w-full" required>
                </div>

                {{-- Tanggal --}}
                <div>
                    <label class="block text-xs font-medium mb-1.5" style="color:var(--text-secondary);">
                        Tanggal Masuk <span style="color:#ef4444;">*</span>
                    </label>
                    <input type="date" name="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}"
                           max="{{ date('Y-m-d') }}" class="form-input w-full" required>
                </div>

                {{-- Supplier --}}
                <div>
                    <label class="block text-xs font-medium mb-1.5" style="color:var(--text-secondary);">
                        Supplier (opsional)
                    </label>
                    <input type="text" name="supplier" value="{{ old('supplier') }}"
                           placeholder="Nama supplier / vendor" class="form-input w-full">
                </div>

                {{-- Keterangan --}}
                <div>
                    <label class="block text-xs font-medium mb-1.5" style="color:var(--text-secondary);">
                        Keterangan (opsional)
                    </label>
                    <textarea name="keterangan" rows="3" class="form-input w-full resize-none"
                              placeholder="Contoh: PO-2026-001, restock bulanan...">{{ old('keterangan') }}</textarea>
                </div>

                {{-- Foto Bukti (WAJIB) --}}
                <div>
                    <label class="block text-xs font-medium mb-1.5" style="color:var(--text-secondary);">
                        <i class="fas fa-camera mr-1"></i> Foto Bukti <span style="color:#ef4444;">*</span>
                        <span class="text-[10px] font-normal ml-1">(maks. 2MB, format: jpg/png/webp)</span>
                    </label>

                    {{-- Upload Zone --}}
                    <div id="uploadZone"
                         class="relative border-2 border-dashed rounded-xl p-5 text-center cursor-pointer transition-all duration-200"
                         style="border-color:var(--border-color);background:var(--bg-primary);"
                         onclick="document.getElementById('foto_bukti').click()"
                         ondragover="handleDragOver(event)"
                         ondragleave="handleDragLeave(event)"
                         ondrop="handleDrop(event)">

                        {{-- Placeholder saat belum ada file --}}
                        <div id="uploadPlaceholder">
                            <div class="text-3xl mb-2"><i class="fas fa-camera text-gray-400"></i></div>
                            <p class="text-sm font-medium" style="color:var(--text-primary);">Klik atau seret gambar ke sini</p>
                            <p class="text-xs mt-1" style="color:var(--text-secondary);">JPG, PNG, WEBP &mdash; Maks. 2MB</p>
                        </div>

                        {{-- Preview saat file dipilih --}}
                        <div id="previewWrapper" class="hidden">
                            <img id="previewImg" src="" alt="Preview" class="mx-auto rounded-lg max-h-48 object-contain mb-3">
                            <p id="previewName" class="text-xs font-medium truncate" style="color:var(--text-secondary);"></p>
                            <button type="button" id="btnHapusFoto"
                                    class="mt-2 text-xs px-3 py-1 rounded-lg font-semibold"
                                    style="background:rgba(239,68,68,0.15);color:#ef4444;border:1px solid rgba(239,68,68,0.25);"
                                    onclick="hapusFoto(event)">
                                <i class="fas fa-trash-alt mr-1"></i> Hapus Foto
                            </button>
                        </div>
                    </div>

                    <input type="file" id="foto_bukti" name="foto_bukti"
                           accept="image/jpg,image/jpeg,image/png,image/webp"
                           class="hidden" onchange="previewFoto(this)" required>

                    @error('foto_bukti')
                    <p class="text-xs mt-1" style="color:#ef4444;">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Tombol --}}
                <div class="flex flex-col sm:flex-row gap-2 justify-end pt-3"
                     style="border-top:1px solid var(--border-color);">
                    <button type="button" onclick="resetFormMasuk()" class="btn-secondary w-full sm:w-auto px-5 py-2 text-sm">Reset</button>
                    <button type="button" onclick="konfirmasiSubmitMasuk()"
                            class="btn-primary w-full sm:w-auto px-5 py-2 text-sm">
                        <i class="fas fa-save mr-2"></i> Simpan Masuk
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Riwayat hari ini --}}
    <div class="section-card">
        <div class="section-head px-4 sm:px-5 py-3 sm:py-4">
            <div class="section-title">Riwayat Masuk Hari Ini</div>
        </div>
        <div class="overflow-x-auto">
            <table class="data-table w-full">
                <thead>
                    <tr>
                        <th>Waktu</th>
                        <th>Barang</th>
                        <th>Jml</th>
                        <th>Supplier</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                    $hariIni = \App\Models\BarangMasuk::with('barang')
                        ->whereDate('tanggal', today())
                        ->latest()
                        ->take(10)
                        ->get();
                    @endphp
                    @if($hariIni->isNotEmpty())
                    @foreach($hariIni as $item)
                    <tr>
                        <td class="mono text-xs">{{ $item->created_at->format('H:i') }}</td>
                        <td>{{ $item->barang->nama_barang ?? '-' }}</td>
                        <td class="mono font-bold" style="color:#10b981;">+{{ $item->jumlah }}</td>
                        <td class="text-xs" style="color:var(--text-secondary);">{{ $item->supplier ?? '-' }}</td>
                    </tr>
                    @endforeach
                    @else
                    <tr>
                        <td colspan="4" class="text-center py-6 text-xs" style="color:var(--text-secondary);">
                            Belum ada transaksi hari ini.
                        </td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Modal Konfirmasi Submit Masuk --}}
<div id="modalKonfirmasiMasuk"
     class="fixed inset-0 z-[200] hidden items-center justify-center"
     style="background:rgba(0,0,0,0.55);backdrop-filter:blur(4px);">
    <div class="rounded-2xl shadow-2xl p-6 w-[90vw] max-w-sm mx-auto text-center animate-in"
         style="background:var(--bg-secondary);border:1px solid var(--border-color);">
        <div class="text-4xl mb-3"><i class="fas fa-arrow-down text-success"></i></div>
        <h3 class="text-base font-bold mb-1">Konfirmasi Simpan Barang Masuk</h3>
        <p class="text-xs mb-5" style="color:var(--text-secondary);">Pastikan semua data sudah benar sebelum menyimpan.</p>
        <div class="flex gap-3 justify-center">
            <button type="button" onclick="tutupModalMasuk()"
                    class="btn-secondary px-5 py-2 text-sm">Batal</button>
            <button type="button" onclick="document.getElementById('formMasuk').submit()"
                    class="btn-primary px-5 py-2 text-sm"><i class="fas fa-check mr-2"></i> Ya, Simpan</button>
        </div>
    </div>
</div>

<script>
// ========== Stok Info ==========
document.getElementById('barang_id').addEventListener('change', function () {
    const selected = this.options[this.selectedIndex];
    const info = document.getElementById('stokInfo');
    const stokNow = document.getElementById('stokNow');
    if (this.value) {
        stokNow.textContent = `${selected.dataset.stok} ${selected.dataset.satuan}`;
        info.classList.remove('hidden');
    } else {
        info.classList.add('hidden');
    }
});

// ========== Preview Foto ==========
function previewFoto(input) {
    if (!input.files || !input.files[0]) return;
    const file = input.files[0];
    const reader = new FileReader();
    reader.onload = function(e) {
        document.getElementById('previewImg').src = e.target.result;
        document.getElementById('previewName').textContent = file.name + ' (' + (file.size / 1024).toFixed(0) + ' KB)';
        document.getElementById('uploadPlaceholder').classList.add('hidden');
        document.getElementById('previewWrapper').classList.remove('hidden');
        document.getElementById('uploadZone').style.borderColor = '#10b981';
        document.getElementById('uploadZone').style.borderStyle = 'solid';
    };
    reader.readAsDataURL(file);
}

function hapusFoto(event) {
    event.stopPropagation();
    document.getElementById('foto_bukti').value = '';
    document.getElementById('previewImg').src = '';
    document.getElementById('previewWrapper').classList.add('hidden');
    document.getElementById('uploadPlaceholder').classList.remove('hidden');
    document.getElementById('uploadZone').style.borderColor = 'var(--border-color)';
    document.getElementById('uploadZone').style.borderStyle = 'dashed';
}

// ========== Drag & Drop ==========
function handleDragOver(e) {
    e.preventDefault();
    document.getElementById('uploadZone').style.borderColor = '#6366f1';
    document.getElementById('uploadZone').style.background = 'rgba(99,102,241,0.06)';
}
function handleDragLeave(e) {
    document.getElementById('uploadZone').style.borderColor = 'var(--border-color)';
    document.getElementById('uploadZone').style.background = 'var(--bg-primary)';
}
function handleDrop(e) {
    e.preventDefault();
    handleDragLeave(e);
    const file = e.dataTransfer.files[0];
    if (file && file.type.startsWith('image/')) {
        const dt = new DataTransfer();
        dt.items.add(file);
        const input = document.getElementById('foto_bukti');
        input.files = dt.files;
        previewFoto(input);
    }
}

// ========== Reset ==========
function resetFormMasuk() {
    document.getElementById('formMasuk').reset();
    document.getElementById('stokInfo').classList.add('hidden');
    hapusFoto({ stopPropagation: () => {} });
}

// ========== Modal Konfirmasi ==========
function konfirmasiSubmitMasuk() {
    // Validasi HTML5 dulu
    const form = document.getElementById('formMasuk');
    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }
    const fotoInput = document.getElementById('foto_bukti');
    if (!fotoInput.files || fotoInput.files.length === 0) {
        alert('Foto bukti wajib diunggah.');
        return;
    }
    document.getElementById('modalKonfirmasiMasuk').classList.remove('hidden');
    document.getElementById('modalKonfirmasiMasuk').classList.add('flex');
}
function tutupModalMasuk() {
    document.getElementById('modalKonfirmasiMasuk').classList.add('hidden');
    document.getElementById('modalKonfirmasiMasuk').classList.remove('flex');
}
// Tutup modal klik luar
document.getElementById('modalKonfirmasiMasuk').addEventListener('click', function(e) {
    if (e.target === this) tutupModalMasuk();
});
</script>
@endsection