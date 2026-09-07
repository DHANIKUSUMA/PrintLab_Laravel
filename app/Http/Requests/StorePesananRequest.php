<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePesananRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Ubah menjadi true agar request diizinkan
    }

    public function rules(): array
    {
        return [
            'id_user' => 'required|exists:users,id_user',
            'id_jenis_kertas' => 'required|exists:jenis_kertas,id_jenis_kertas',
            'jumlah_lembar' => 'required|integer|min:1',
        ];
    }

    // (Opsional) Pesan error kustom dalam bahasa Indonesia
    public function messages(): array
    {
        return [
            'id_user.required' => 'Data user wajib diisi.',
            'id_jenis_kertas.required' => 'Pilih jenis kertas terlebih dahulu.',
            'jumlah_lembar.required' => 'Jumlah lembar wajib diisi.',
            'jumlah_lembar.min' => 'Jumlah lembar minimal adalah 1.',
        ];
    }
}