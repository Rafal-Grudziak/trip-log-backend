<?php

namespace App\Dtos;

use Illuminate\Support\Collection;

readonly class TravelStoreDto
{
    public Collection $places;

    public function __construct(
        public string $name,
        public string $from,
        public string $to,
        public float $longitude,
        public float $latitude,
        array $places = [],
        public ?string $description = null,
    )
    {
        $this->processPlaces($places);
    }

    protected function processPlaces(array $places): void
    {
        foreach ($places as $key => $place) {
            $places[$key] = new PlaceStoreDto(...$place);
        }
        $this->places = collect($places);
    }

}
