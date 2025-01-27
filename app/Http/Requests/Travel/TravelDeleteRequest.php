<?php

namespace App\Http\Requests\Travel;

use App\Models\Travel;
use Illuminate\Foundation\Http\FormRequest;

class TravelDeleteRequest extends FormRequest
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
        return [];
    }
}
