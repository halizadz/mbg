@extends('layouts.app')

@section('title', 'Tambah Barang Keluar')
@section('breadcrumb', 'Beranda / Transaksi / Keluar / Tambah')

@section('content')

<div class="flex items-center justify-between mb-5">
    <div>
        <h2 class="text-[22px] font-bold tracking-[-0.3px]"><i class="fas fa-arrow-up text-danger mr-2"></i> Tambah Barang Keluar</h2>
        <p class="text-[13px] mt-1" style="color:var(--text-secondary);">Catat pengeluaran barang dari gudang</p>
    </div>
    <a href="{{ route('transaksi.keluar') }}" class="btn-secondary text-sm px-4 py-2">&larr; Kembali</a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
    {{-- Form --}}
    <div class="section-card">
        <div class="section-head px-4 sm:px-5 py-3 sm:py-4">
            <div class="section-title">Form Barang Keluar</div>
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

            <form id="formKeluar" action="{{ route('transaksi.keluar.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
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

                {{-- Warning stok --}}
                <div id="stokWarning" class="rounded-lg p-3 text-xs hidden"
                     style="background:rgba(239,68,68,0.08);border:1px solid rgba(239,68,68,0.25);color:#ef4444;">
                    <i class="fas fa-exclamation-triangle mr-1"></i> Stok tersedia: <strong class="font-mono" id="stokNow">-</strong> &mdash; pastikan tidak melebihi stok!
                </div>

                {{-- Jumlah --}}
                <div>
                    <label class="block text-xs font-medium mb-1.5" style="color:var(--text-secondary);">
                        Jumlah Keluar <span style="color:#ef4444;">*</span>
                    </label>
                    <input type="number" name="jumlah" id="jumlah" min="1"
                           value="{{ old('jumlah') }}" placeholder="0"
                           class="form-input w-full" required>
                    <p id="jumlahError" class="text-xs mt-1 hidden" style="color:#ef4444;"></p>
                </div>

                {{-- Tanggal --}}
                <div>
                    <label class="block text-xs font-medium mb-1.5" style="color:var(--text-secondary);">
                        Tanggal Keluar <span style="color:#ef4444;">*</span>
                    </label>
                    <input type="date" name="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}"
                           max="{{ date('Y-m-d') }}" class="form-input w-full" required>
                </div>

                {{-- Keterangan --}}
                <div>
                    <label class="block text-xs font-medium mb-1.5" style="color:var(--text-secondary);">
                        Keterangan (opsional)
                    </label>
                    <textarea name="keterangan" rows="3" class="form-input w-full resize-none"
                              placeholder="Contoh: Digunakan oleh Dept. HRD, pemohon: Budi S.">{{ old('keterangan') }}</textarea>
                </div>

                {{-- Foto Bukti (WAJIB) --}}
                <div>
                    <label class="block text-xs font-medium mb-1.5" style="color:var(--text-secondary);">
                        <i class="fas fa-camera mr-1"></i> Foto Bukti <span style="color:#ef4444;">*</span>
                        <span class="text-[10px] font-normal ml-1">(maks. 2MB, format: jpg/png/webp)</span>
                    </label>

                    {{-- Upload Zone --}}
                    <div id="uploadZoneKeluar"
                         class="relative border-2 border-dashed rounded-xl p-5 text-center cursor-pointer transition-all duration-200"
                         style="border-color:var(--border-color);background:var(--bg-primary);"
                         onclick="document.getElementById('foto_bukti').click()"
                         ondragover="handleDragOver(event, 'uploadZoneKeluar')"
                         ondragleave="handleDragLeave(event, 'uploadZoneKeluar')"
                         ondrop="handleDrop(event)">

                        <div id="uploadPlaceholderK">
                            <div class="text-3xl mb-2"><i class="fas fa-camera text-gray-400"></i></div>
                            <p class="text-sm font-medium" style="color:var(--text-primary);">Klik atau seret gambar ke sini</p>
                            <p class="text-xs mt-1" style="color:var(--text-secondary);">JPG, PNG, WEBP &mdash; Maks. 2MB</p>
                        </div>

                        <div id="previewWrapperK" class="hidden">
                            <img id="previewImgK" src="" alt="Preview" class="mx-auto rounded-lg max-h-48 object-contain mb-3">
                            <p id="previewNameK" class="text-xs font-medium truncate" style="color:var(--text-secondary);"></p>
                            <button type="button" id="btnHapusFotoK"
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
                    <button type="button" onclick="resetFormKeluar()"
                            class="btn-secondary w-full sm:w-auto px-5 py-2 text-sm">Reset</button>
                    <button type="button" id="btnSubmit" onclick="konfirmasiSubmitKeluar()"
                            class="btn-primary w-full sm:w-auto px-5 py-2 text-sm"
                            style="background:#ef4444;">
                        <i class="fas fa-save mr-2"></i> Simpan Keluar
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Riwayat hari ini --}}
    <div class="section-card">
        <div class="section-head px-4 sm:px-5 py-3 sm:py-4">
            <div class="section-title">Riwayat Keluar Hari Ini</div>
        </div>
        <div class="overflow-x-auto">
            <table class="data-table w-full">
                <thead>
                    <tr>
                        <th>Waktu</th>
                        <th>Barang</th>
                        <th>Jml</th>
                        <th>Stok Sisa</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                    $hariIni = \App\Models\BarangKeluar::with('barang')
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
                        <td class="mono font-bold" style="color:#ef4444;">-{{ $item->jumlah }}</td>
                        <td class="mono {{ ($item->barang->stok ?? 0) <= ($item->barang->stok_minimum ?? 0) ? 'text-danger' : '' }}">
                            {{ $item->barang->stok ?? '-' }}
                        </td>
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
        @if($hariIni->isNotEmpty())
        <div class="px-5 py-3 text-xs" style="border-top:1px solid var(--border-color);color:var(--text-secondary);">
            Total keluar hari ini: <strong style="color:#ef4444;">-{{ $hariIni->sum('jumlah') }} item</strong>
        </div>
        @endif
    </div>
</div>

{{-- Modal Konfirmasi Submit Keluar --}}
<div id="modalKonfirmasiKeluar"
     class="fixed inset-0 z-[200] hidden items-center justify-center"
     style="background:rgba(0,0,0,0.55);backdrop-filter:blur(4px);">
    <div class="rounded-2xl shadow-2xl p-6 w-[90vw] max-w-sm mx-auto text-center animate-in"
         style="background:var(--bg-secondary);border:1px solid var(--border-color);">
        <div class="text-4xl mb-3"><i class="fas fa-arrow-up text-danger"></i></div>
        <h3 class="text-base font-bold mb-1">Konfirmasi Simpan Barang Keluar</h3>
        <p class="text-xs mb-5" style="color:var(--text-secondary);">Pastikan semua data sudah benar sebelum menyimpan.</p>
        <div class="flex gap-3 justify-center">
            <button type="button" onclick="tutupModalKeluar()"
                    class="btn-secondary px-5 py-2 text-sm">Batal</button>
            <button type="button" onclick="document.getElementById('formKeluar').submit()"
                    class="btn-primary px-5 py-2 text-sm" style="background:#ef4444;"><i class="fas fa-check mr-2"></i> Ya, Simpan</button>
        </div>
    </div>
</div>

<script>
// ========== Stok Warning ==========
let currentStok = 0;
document.getElementById('barang_id').addEventListener('change', function () {
    const selected = this.options[this.selectedIndex];
    const warning = document.getElementById('stokWarning');
    const stokNow = document.getElementById('stokNow');
    if (this.value) {
        currentStok = parseInt(selected.dataset.stok) || 0;
        stokNow.textContent = `${currentStok} ${selected.dataset.satuan}`;
        warning.classList.remove('hidden');
    } else {
        currentStok = 0;
        warning.classList.add('hidden');
    }
});

document.getElementById('jumlah').addEventListener('input', function () {
    const err = document.getElementById('jumlahError');
    if (currentStok > 0 && parseInt(this.value) > currentStok) {
        err.textContent = `Jumlah melebihi stok tersedia (${currentStok})!`;
        err.classList.remove('hidden');
    } else {
        err.classList.add('hidden');
    }
});

// ========== Preview Foto ==========
function previewFoto(input) {
    if (!input.files || !input.files[0]) return;
    const file = input.files[0];
    const reader = new FileReader();
    reader.onload = function(e) {
        document.getElementById('previewImgK').src = e.target.result;
        document.getElementById('previewNameK').textContent = file.name + ' (' + (file.size / 1024).toFixed(0) + ' KB)';
        document.getElementById('uploadPlaceholderK').classList.add('hidden');
        document.getElementById('previewWrapperK').classList.remove('hidden');
        document.getElementById('uploadZoneKeluar').style.borderColor = '#10b981';
        document.getElementById('uploadZoneKeluar').style.borderStyle = 'solid';
    };
    reader.readAsDataURL(file);
}

function hapusFoto(event) {
    event.stopPropagation();
    document.getElementById('foto_bukti').value = '';
    document.getElementById('previewImgK').src = '';
    document.getElementById('previewWrapperK').classList.add('hidden');
    document.getElementById('uploadPlaceholderK').classList.remove('hidden');
    document.getElementById('uploadZoneKeluar').style.borderColor = 'var(--border-color)';
    document.getElementById('uploadZoneKeluar').style.borderStyle = 'dashed';
}

// ========== Drag & Drop ==========
function handleDragOver(e, zoneId) {
    e.preventDefault();
    document.getElementById(zoneId).style.borderColor = '#6366f1';
    document.getElementById(zoneId).style.background = 'rgba(99,102,241,0.06)';
}
function handleDragLeave(e, zoneId) {
    document.getElementById(zoneId).style.borderColor = 'var(--border-color)';
    document.getElementById(zoneId).style.background = 'var(--bg-primary)';
}
function handleDrop(e) {
    e.preventDefault();
    const file = e.dataTransfer.files[0];
    if (file && file.type.startsWith('image/')) {
        const dt = new DataTransfer();
        dt.items.add(file);
        const input = document.getElementById('foto_bukti');
        input.files = dt.files;
        previewFoto(input);
    }
    document.getElementById('uploadZoneKeluar').style.background = 'var(--bg-primary)';
}

// ========== Reset ==========
function resetFormKeluar() {
    document.getElementById('formKeluar').reset();
    document.getElementById('stokWarning').classList.add('hidden');
    document.getElementById('jumlahError').classList.add('hidden');
    hapusFoto({ stopPropagation: () => {} });
    currentStok = 0;
}

// ========== Modal Konfirmasi ==========
function konfirmasiSubmitKeluar() {
    const form = document.getElementById('formKeluar');
    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }
    const fotoInput = document.getElementById('foto_bukti');
    if (!fotoInput.files || fotoInput.files.length === 0) {
        alert('Foto bukti wajib diunggah.');
        return;
    }
    document.getElementById('modalKonfirmasiKeluar').classList.remove('hidden');
    document.getElementById('modalKonfirmasiKeluar').classList.add('flex');
}
function tutupModalKeluar() {
    document.getElementById('modalKonfirmasiKeluar').classList.add('hidden');
    document.getElementById('modalKonfirmasiKeluar').classList.remove('flex');
}
document.getElementById('modalKonfirmasiKeluar').addEventListener('click', function(e) {
    if (e.target === this) tutupModalKeluar();
});
</script>
@endsection