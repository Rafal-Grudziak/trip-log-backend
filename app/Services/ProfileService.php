<?php

namespace App\Services;

use App\Http\DTOs\ProfileUpdateDto;
use App\Models\User;

class ProfileService
{

    public function updateProfile(User $user, ProfileUpdateDto $dto, $avatar): User
    {
        if($avatar) {
            $path = $avatar->store('avatars', 'public');
        }

        $user = $this->setUserValues($user, $dto, $path ?? null);
        $user->save();

        return $user;
    }

    private function setUserValues(User $user, ProfileUpdateDto $dto, ?string $avatar): User
    {
        $user->setAttribute('email', $dto->email);
        $user->setAttribute('name', $dto->name);
        $user->setAttribute('bio', $dto->bio);
        $user->setAttribute('facebook_link', $dto->facebook_link);
        $user->setAttribute('instagram_link', $dto->instagram_link);
        $user->setAttribute('x_link', $dto->x_link);
        $user->setAttribute('avatar', $avatar);
        $user->travelPreferences()->whereNotIn('travel_preferences.id', $dto->travel_preferences)->delete();
        $user->travelPreferences()->syncWithoutDetaching($dto->travel_preferences);

        return $user;
    }

}
