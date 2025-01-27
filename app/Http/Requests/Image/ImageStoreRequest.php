<?php

namespace App\Http\Requests\Image;

use App\Models\Place;
use App\Models\Travel;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ImageStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'imageable_type' => ['required', 'string',  Rule::in([Travel::class, Place::class])],
            'imageable_id' => ['required', 'integer'],
            'image' => ['required', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
        ];
    }
}
