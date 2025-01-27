<?php

namespace App\Dtos;

readonly class PlaceStoreDto
{
    public function __construct(
        public string $name,
        public int $category_id,
        public float $longitude,
        public float $latitude,
        public ?string $description = null,
        public ?int $id = null,
    )
    {
    }
}
