<?php

// app/Http/Requests/UpdateBookRequest.php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBookRequest extends FormRequest
{
    public function authorize()
    {
        return true; 
    }

    public function rules()
    {
        return [
            'title' => 'sometimes|required|string|max:255',
            'summary' => 'sometimes|required|string',
            'image' => 'sometimes|required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'stok' => 'sometimes|required|integer',
            'category_id' => 'sometimes|required|uuid',
        ];
    }
}

