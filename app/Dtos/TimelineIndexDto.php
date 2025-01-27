<?php

namespace App\Dtos;

readonly class TimelineIndexDto implements FilterDto
{
    public function __construct(
        public readonly int $page = 1,
        public readonly ?string $date_from = null,
        public readonly ?string $date_to = null,
        public readonly ?string $sort_direction = null,
    ) {}
}
