<?php

namespace App\Dtos;

use Illuminate\Http\UploadedFile;

readonly class ProfileUpdateDto
{
    public function __construct(
        public ?string $email = null,
        public ?string $name = null,
        public ?string $bio = null,
        public ?string $facebook_link = null,
        public ?string $instagram_link = null,
        public ?string $x_link = null,
        public ?array  $travel_preferences = null,
        public ?UploadedFile $avatar = null,
    )
    {
    }
}
