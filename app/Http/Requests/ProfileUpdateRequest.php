<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileUpdateRequest extends FormRequest
{
    public function rules()
    {
        return [
            'email' => 'required|email',
            'name' => 'required|max:32|unique:users,name,' . $this->user()->id,
//            'avatar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'facebook_link' => 'nullable|url|max:128',
            'instagram_link' => 'nullable|url|max:128',
            'x_link' => 'nullable|url|max:128',
            'bio' => 'nullable|max:300',
            'travel_preferences' => 'nullable|array',
        ];
    }
}
