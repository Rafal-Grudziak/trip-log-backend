<?php

namespace App\Http\Requests\Travel;

use App\Models\Travel;
use Illuminate\Foundation\Http\FormRequest;


class TravelUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        $travel = $this->route('travel');

        if (!$travel) {
            return false;
        }

        return $travel->user_id === auth()->id();
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:512'],
            'from' => ['required', 'date'],
            'to' => ['required', 'date', 'after:from'],
            'longitude' => ['required', 'numeric'],
            'latitude' => ['required', 'numeric'],
            'places' => ['sometimes'],
            'places.*.id' => ['nullable', 'integer'],
            'places.*.name' => ['required', 'string', 'max:127'],
            'places.*.description' => ['nullable', 'string', 'max:255'],
            'places.*.category_id' => ['required', 'exists:travel_categories,id'],
            'places.*.longitude' => ['required', 'numeric'],
            'places.*.latitude' => ['required', 'numeric'],
        ];
    }
}
