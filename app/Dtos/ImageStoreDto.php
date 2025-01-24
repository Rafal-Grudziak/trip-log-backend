<?php

namespace App\Dtos;

use Illuminate\Http\UploadedFile;

readonly class ImageStoreDto
{
    public function __construct(
        public ?string $imageable_type = null,
        public ?int $imageable_id = null,
        public ?UploadedFile $image = null,
    )
    {
    }
}
