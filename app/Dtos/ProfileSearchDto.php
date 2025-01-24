<?php

namespace App\Dtos;

readonly class ProfileSearchDto
{
    public function __construct(
        public int $page = 1,
        public ?string $query = null
    )
    {
    }
}
