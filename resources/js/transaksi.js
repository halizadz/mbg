/**
 * transaksi.js
 * AJAX stok info & validasi jumlah keluar ≤ stok tersedia
 */

(function () {
    const selectBarang  = document.getElementById('barang_id');
    const inputJumlah   = document.getElementById('jumlah');
    const stokWarning   = document.getElementById('stokWarning');
    const stokNow       = document.getElementById('stokNow');
    const jumlahError   = document.getElementById('jumlahError');
    const btnSubmit     = document.getElementById('btnSubmit');

    if (!selectBarang) return; // hanya jalan di halaman keluar/create

    let stokTersedia = 0;

    // Saat barang dipilih — tampilkan info stok
    selectBarang.addEventListener('change', function () {
        const selected = this.options[this.selectedIndex];
        stokTersedia   = parseInt(selected.dataset.stok) || 0;
        const satuan   = selected.dataset.satuan || '';

        if (this.value) {
            stokNow.textContent = `${stokTersedia} ${satuan}`;
            stokWarning.classList.remove('hidden');

            // Warna warning sesuai kondisi stok
            if (stokTersedia <= 5) {
                stokWarning.style.color = '#ef4444';
                stokWarning.style.background = 'rgba(239,68,68,0.08)';
                stokWarning.style.borderColor = 'rgba(239,68,68,0.25)';
            } else {
                stokWarning.style.color = '#10b981';
                stokWarning.style.background = 'rgba(16,185,129,0.08)';
                stokWarning.style.borderColor = 'rgba(16,185,129,0.25)';
            }

            // Update max input jumlah
            inputJumlah.max = stokTersedia;
        } else {
            stokWarning.classList.add('hidden');
            inputJumlah.removeAttribute('max');
            stokTersedia = 0;
        }

        validateJumlah();
    });

    // Validasi realtime saat jumlah diketik
    inputJumlah.addEventListener('input', validateJumlah);

    function validateJumlah() {
        const jumlah = parseInt(inputJumlah.value) || 0;

        if (selectBarang.value && jumlah > stokTersedia) {
            jumlahError.textContent = `Jumlah melebihi stok tersedia (${stokTersedia}).`;
            jumlahError.classList.remove('hidden');
            inputJumlah.style.borderColor = '#ef4444';
            if (btnSubmit) btnSubmit.disabled = true;
        } else {
            jumlahError.classList.add('hidden');
            inputJumlah.style.borderColor = '';
            if (btnSubmit) btnSubmit.disabled = false;
        }
    }

    // Reset form
    window.resetForm = function () {
        stokWarning.classList.add('hidden');
        jumlahError.classList.add('hidden');
        inputJumlah.style.borderColor = '';
        if (btnSubmit) btnSubmit.disabled = false;
        stokTersedia = 0;
    };
})();