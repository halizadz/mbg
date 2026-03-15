@extends('layouts.app')

@section('title', 'Tambah User')
@section('breadcrumb', 'Beranda / Manajemen User / Tambah')

@section('content')
<div class="mb-5 flex items-center gap-3">
    <a href="{{ route('users.index') }}" class="w-8 h-8 rounded-full flex items-center justify-center btn-secondary text-lg">
        &larr;
    </a>
    <div>
        <h2 class="text-[22px] font-bold tracking-[-0.3px]">Tambah User</h2>
        <p class="text-[13px] mt-1" style="color:var(--text-secondary);">Tambahkan akses untuk admin baru</p>
    </div>
</div>

<div class="section-card p-5 sm:p-7 max-w-2xl">
    @if($errors->any())
    <div class="mb-4 px-4 py-3 rounded-lg text-sm" style="background:rgba(239,68,68,0.15);border:1px solid rgba(239,68,68,0.3);color:#ef4444;">
        <ul class="list-disc list-inside space-y-1">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form id="formTambahUser" action="{{ route('users.store') }}" method="POST">
        @csrf

        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium mb-1.5">Nama Lengkap <span style="color:#ef4444;">*</span></label>
                <input type="text" name="name" value="{{ old('name') }}" required
                    class="form-input w-full @error('name') border-red-500 @enderror">
                @error('name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-1.5">Email Akses <span style="color:#ef4444;">*</span></label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                        class="form-input w-full @error('email') border-red-500 @enderror">
                    @error('email') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1.5">Role / Hak Akses <span style="color:#ef4444;">*</span></label>
                    <select name="role" required class="form-input w-full @error('role') border-red-500 @enderror">
                        <option value="operator" {{ old('role') == 'operator' ? 'selected' : '' }}>Operator</option>
                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Administrator</option>
                    </select>
                    @error('role') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                {{-- Password --}}
                <div>
                    <label class="block text-sm font-medium mb-1.5">Password <span style="color:#ef4444;">*</span></label>
                    <div class="relative">
                        <input type="password" name="password" id="inputPassword" required
                            class="form-input w-full pr-10 @error('password') border-red-500 @enderror">
                        <button type="button"
                                class="absolute inset-y-0 right-0 px-3 flex items-center"
                                style="color:var(--text-secondary);"
                                onclick="toggleEye('inputPassword', 'eyePassword')"
                                tabindex="-1"
                                title="Tampilkan/sembunyikan password">
                            <svg id="eyePassword" xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                    </div>
                    @error('password') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Konfirmasi Password --}}
                <div>
                    <label class="block text-sm font-medium mb-1.5">Konfirmasi Password <span style="color:#ef4444;">*</span></label>
                    <div class="relative">
                        <input type="password" name="password_confirmation" id="inputPasswordConf" required
                            class="form-input w-full pr-10">
                        <button type="button"
                                class="absolute inset-y-0 right-0 px-3 flex items-center"
                                style="color:var(--text-secondary);"
                                onclick="toggleEye('inputPasswordConf', 'eyePasswordConf')"
                                tabindex="-1"
                                title="Tampilkan/sembunyikan password">
                            <svg id="eyePasswordConf" xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <div class="pt-4 border-t mt-6 border-slate-700/30 flex justify-end gap-3">
                <a href="{{ route('users.index') }}" class="btn-secondary px-5 py-2.5 text-sm">Batal</a>
                <button type="button" onclick="konfirmasiSimpanUser()"
                        class="btn-primary px-5 py-2.5 text-sm"><i class="fas fa-save mr-2"></i> Simpan User</button>
            </div>
        </div>
    </form>
</div>

{{-- Modal Konfirmasi Simpan --}}
<div id="modalKonfirmasiUser"
     class="fixed inset-0 z-[200] hidden items-center justify-center"
     style="background:rgba(0,0,0,0.55);backdrop-filter:blur(4px);">
    <div class="rounded-2xl shadow-2xl p-6 w-[90vw] max-w-sm mx-auto text-center"
         style="background:var(--bg-secondary);border:1px solid var(--border-color);">
        <div class="text-4xl mb-3"><i class="fas fa-user-plus text-blue-500"></i></div>
        <h3 class="text-base font-bold mb-1">Konfirmasi Tambah User</h3>
        <p class="text-xs mb-5" style="color:var(--text-secondary);">Pastikan data user sudah benar sebelum menyimpan.</p>
        <div class="flex gap-3 justify-center">
            <button type="button" onclick="tutupModalUser()"
                    class="btn-secondary px-5 py-2 text-sm">Batal</button>
            <button type="button" onclick="document.getElementById('formTambahUser').submit()"
                    class="btn-primary px-5 py-2 text-sm"><i class="fas fa-check mr-2"></i> Ya, Simpan</button>
        </div>
    </div>
</div>

<script>
function toggleEye(inputId, iconId) {
    const input = document.getElementById(inputId);
    const icon  = document.getElementById(iconId);
    if (input.type === 'password') {
        input.type = 'text';
        // Ganti ikon ke eye-off (slash)
        icon.innerHTML = `
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7
                     a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878
                     l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59
                     m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025
                     0 01-4.132 5.411m0 0L21 21" />
        `;
    } else {
        input.type = 'password';
        icon.innerHTML = `
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7
                     -1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
        `;
    }
}

function konfirmasiSimpanUser() {
    const form = document.getElementById('formTambahUser');
    if (!form.checkValidity()) { form.reportValidity(); return; }
    document.getElementById('modalKonfirmasiUser').classList.remove('hidden');
    document.getElementById('modalKonfirmasiUser').classList.add('flex');
}
function tutupModalUser() {
    document.getElementById('modalKonfirmasiUser').classList.add('hidden');
    document.getElementById('modalKonfirmasiUser').classList.remove('flex');
}
document.getElementById('modalKonfirmasiUser').addEventListener('click', function(e) {
    if (e.target === this) tutupModalUser();
});
</script>
@endsection
