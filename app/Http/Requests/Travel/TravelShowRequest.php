<?php

namespace App\Http\Requests\Travel;

use App\Models\Travel;
use Illuminate\Foundation\Http\FormRequest;

class TravelShowRequest extends FormRequest
{
    public function authorize(): bool
    {
        $travel = $this->route('travel');

        if (!$travel) {
            return false;
        }

        $user = auth()->user();

        if ($travel->user_id === $user->id) {
            return true;
        }

        $owner = $travel->user;
        $isFriend = $owner->acceptedFriendsAsSender->contains($user)
            || $owner->acceptedFriendsAsReceiver->contains($user);

        return $isFriend;
    }

    public function rules(): array
    {
        return [];
    }
}
