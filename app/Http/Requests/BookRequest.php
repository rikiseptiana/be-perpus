<?php

// app/Http/Requests/StoreBookRequest.php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BookRequest extends FormRequest
{
    public function authorize()
    {
        return true; 
    }

    public function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'summary' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'stok' => 'required|integer',
            'category_id' => 'required|uuid',
        ];
    }
    public function messages(){
        return [
            'title.required' => 'Judul buku harus diisi',
            'title.string' => 'Judul buku harus berupa string',
            'title.max' => 'Judul buku maksimal 255 karakter',

            'summary.required' => 'Summary buku harus diisi',
            'summary.string' => 'Summary buku harus berupa string',

            'image.required' => 'Gambar buku harus diisi',
            'image.image' => 'Gambar buku harus berupa gambar',
            'image.mimes' => 'Gambar buku harus berupa jpeg, png, jpg',
            'image.max' => 'Gambar buku maksimal 2 MB',

            'stok.required' => 'Stok buku harus diisi',
            'stok.integer' => 'Stok buku harus berupa angka',

            'category_id.required' => 'Kategori buku harus diisi',
            'category_id.uuid' => 'Kategori buku harus berupa UUID',
        ];

    }
}
