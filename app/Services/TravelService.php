<?php

namespace App\Services;

use App\Dtos\TravelStoreDto;
use App\Models\Travel;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class TravelService extends ModelService
{
    public function __construct(protected PlaceService $placeService)
    {
    }


    protected function getModelClass(): string
    {
        return Travel::class;
    }

    public function getAll(int $perPage = 10): LengthAwarePaginator
    {
        return Travel::query()
            ->where('user_id', auth()->id())
            ->paginate($perPage);
    }

    public function storeTravel(TravelStoreDto $dto): Travel
    {
        $travel = $this->getModel()->newInstance();
        $travel = $this->setTravelValues($travel, $dto);
        $travel->user_id = Auth::id();
        $travel->favourite = false;
        $travel->save();

        foreach ($dto->places ?? [] as $place) {
            $this->placeService->store($travel, $place);
        }

        return $travel;
    }

    public function updateTravel(TravelStoreDto $dto, Travel $travel): Travel
    {
        $travel = $this->setTravelValues($travel, $dto);
        $travel->save();

        $this->handlePlaces($travel, $dto->places);

        return $travel;
    }

    public function toggleFavourite(Travel $travel): Travel
    {
        $travel->favourite = !$travel->favourite;
        $travel->save();

        return $travel;
    }

    public function deleteTravel(Travel $travel): void
    {
        foreach ($travel->images as $image) {
            File::delete(storage_path('app/public/'.$image->path));
            $image->delete();
        }

        foreach ($travel->places as $place) {
            foreach ($place->images as $image) {
                File::delete(storage_path('app/public/'.$image->path));
                $image->delete();
            }
            $place->delete();
        }

        $travel->delete();
    }

    private function setTravelValues(Travel $travel, TravelStoreDto $dto): Travel
    {
        $travel->setAttribute('name', $dto->name);
        $travel->setAttribute('description', $dto->description);
        $travel->setAttribute('from', $dto->from);
        $travel->setAttribute('to', $dto->to);
        $travel->setAttribute('longitude', $dto->longitude);
        $travel->setAttribute('latitude', $dto->latitude);

        return $travel;
    }

    protected function handlePlaces(Travel $travel, Collection $places): void
    {
        $travel->places()->whereNotIn('id', $places->pluck('id')->filter(fn ($item) => $item !== null))->delete();

        foreach ($places->whereNotNull('id') as $place) {
            $this->placeService->update($place, $travel->places()->find($place->id));
        }

        foreach ($places->whereNull('id') as $place) {
            $this->placeService->store($travel, $place);
        }
    }


}
