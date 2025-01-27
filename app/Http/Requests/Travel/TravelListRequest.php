<?php

namespace App\Http\Requests\Travel;

use App\Models\Travel;
use Illuminate\Foundation\Http\FormRequest;

class TravelListRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->route('user');

        if (!$user) {
            return false;
        }

        $auth = auth()->user();

        if ($user->id === $auth->id) {
            return true;
        }

        $owner = $user;
        $isFriend = $owner->acceptedFriendsAsSender->contains($auth)
            || $owner->acceptedFriendsAsReceiver->contains($auth);

        return $isFriend;
    }

    public function rules(): array
    {
        return [];
    }
}
