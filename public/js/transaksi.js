// Tampilkan preview gambar untuk input file foto bukti
function previewImage(input) {
    const preview = document.getElementById('preview');
    if (input.files && input.files[0]) {
        const reader = new FileReader();

        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.classList.remove('hidden');
        }

        reader.readAsDataURL(input.files[0]);
    } else {
        preview.src = '';
        preview.classList.add('hidden');
    }
}

// Reset form dan sembunyikan info stok/preview
function resetForm() {
    // Sembunyikan stok info di barang masuk
    const stokInfo = document.getElementById('stokInfo');
    if (stokInfo) stokInfo.classList.add('hidden');

    // Sembunyikan stok warning di barang keluar
    const stokWarning = document.getElementById('stokWarning');
    if (stokWarning) stokWarning.classList.add('hidden');

    // Sembunyikan error jumlah
    const jumlahError = document.getElementById('jumlahError');
    if (jumlahError) jumlahError.classList.add('hidden');

    // Sembunyikan preview gambar
    const preview = document.getElementById('preview');
    if (preview) {
        preview.src = '';
        preview.classList.add('hidden');
    }
}

// Event listener untuk info stok saat pilih barang (Masuk & Keluar)
document.addEventListener('DOMContentLoaded', function() {
    const barangSelect = document.getElementById('barang_id');

    if (barangSelect) {
        barangSelect.addEventListener('change', function() {
            const selected = this.options[this.selectedIndex];

            // Info stok untuk barang masuk
            const stokInfo = document.getElementById('stokInfo');
            const stokNowInfo = document.getElementById('stokNow');

            if (stokInfo && stokNowInfo) {
                if (this.value) {
                    const stok = selected.dataset.stok;
                    const satuan = selected.dataset.satuan;
                    stokNowInfo.textContent = `${stok} ${satuan}`;
                    stokInfo.classList.remove('hidden');
                } else {
                    stokInfo.classList.add('hidden');
                }
            }

            // Info stok untuk barang keluar
            const stokWarning = document.getElementById('stokWarning');
            const stokNowWarning = document.getElementById('stokNow'); // this ID is reused in Keluar view but we'll check existence

            // We need to check if we are in Keluar view (has stokWarning)
            if (stokWarning) {
                 if (this.value) {
                    const stok = selected.dataset.stok;
                    const satuan = selected.dataset.satuan;
                    
                    // The Keluar view reuses 'stokNow' ID or we can just find it inside stokWarning
                    const stokStrong = stokWarning.querySelector('strong');
                    if(stokStrong) stokStrong.textContent = `${stok} ${satuan}`;
                    
                    stokWarning.classList.remove('hidden');

                    // Validasi max value pada input jumlah
                    const inputJumlah = document.getElementById('jumlah');
                    if (inputJumlah) {
                        inputJumlah.max = stok;
                    }
                } else {
                    stokWarning.classList.add('hidden');
                    const inputJumlah = document.getElementById('jumlah');
                    if (inputJumlah) {
                        inputJumlah.removeAttribute('max');
                    }
                }
            }
        });
    }

    // Validasi real-time jumlah vs stok (Khusus Barang Keluar)
    const inputJumlah = document.getElementById('jumlah');
    const barangSelectKeluar = document.getElementById('barang_id');
    const jumlahError = document.getElementById('jumlahError');
    const btnSubmit = document.getElementById('btnSubmit');

    if (inputJumlah && barangSelectKeluar && jumlahError && btnSubmit) {
        // Only apply if we are in 'Keluar' view (btnSubmit usually exists there with particular style)
        inputJumlah.addEventListener('input', function() {
            if (!barangSelectKeluar.value) return;

            const selected = barangSelectKeluar.options[barangSelectKeluar.selectedIndex];
            const maxStok = parseInt(selected.dataset.stok, 10);
            const inputVal = parseInt(this.value, 10);

            if (inputVal > maxStok) {
                jumlahError.textContent = `Jumlah melebihi stok yang ada (${maxStok}).`;
                jumlahError.classList.remove('hidden');
                this.classList.add('border-red-500', 'focus:ring-red-500');
                btnSubmit.disabled = true;
                btnSubmit.classList.add('opacity-50', 'cursor-not-allowed');
            } else {
                jumlahError.classList.add('hidden');
                this.classList.remove('border-red-500', 'focus:ring-red-500');
                btnSubmit.disabled = false;
                btnSubmit.classList.remove('opacity-50', 'cursor-not-allowed');
            }
        });
    }
});
