<?php

namespace App\Http\Requests\Profile;

use Illuminate\Foundation\Http\FormRequest;

class ProfileSearchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules()
    {
        return [
            'page' => ['nullable', 'integer', 'min:1'],
            'query' => ['required', 'max:32']
        ];
    }
}
