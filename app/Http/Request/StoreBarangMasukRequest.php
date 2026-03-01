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
            'barang_id'  => ['required', 'integer', 'exists:barang,id'],
            'jumlah'     => ['required', 'integer', 'min:1'],
            'tanggal'    => ['required', 'date', 'before_or_equal:today'],
            'supplier'   => ['nullable', 'string', 'max:255'],
            'keterangan' => ['nullable', 'string', 'max:500'],
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
        ];
    }
}