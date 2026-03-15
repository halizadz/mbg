<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBarangMasukRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'barang_id'  => ['required', 'integer', 'exists:barangs,id'],
            'jumlah'     => ['required', 'integer', 'min:1'],
            'tanggal'    => ['required', 'date', 'before_or_equal:today'],
            'supplier'   => ['nullable', 'string', 'max:255'],
            'keterangan' => ['nullable', 'string', 'max:500'],
            'foto_bukti' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ];
    }

    public function messages(): array
    {
        return [
            'barang_id.required' => 'Barang harus dipilih.',
            'barang_id.exists'   => 'Barang tidak ditemukan.',
            'jumlah.required'    => 'Jumlah harus diisi.',
            'jumlah.min'         => 'Jumlah minimal 1.',
            'tanggal.required'   => 'Tanggal harus diisi.',
            'tanggal.before_or_equal' => 'Tanggal tidak boleh lebih dari hari ini.',
            'foto_bukti.required'     => 'Foto bukti wajib diunggah.',
            'foto_bukti.image'        => 'File harus berupa gambar.',
            'foto_bukti.mimes'        => 'Format gambar harus jpg, jpeg, png, atau webp.',
            'foto_bukti.max'          => 'Ukuran gambar maksimal 2MB.',
        ];
    }
}