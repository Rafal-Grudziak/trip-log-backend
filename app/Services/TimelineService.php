<?php

namespace App\Services;

use App\Filters\BaseIndexFilter;
use App\Filters\TimelineIndexFilter;
use App\Http\DTOs\TimelineIndexDTO;
use App\Models\Travel;

class TimelineService extends ModelService
{

    protected function getModelClass(): string
    {
        return Travel::class;
    }

    public function filter(TimelineIndexDTO $dto): BaseIndexFilter
    {
        return app()->make(TimelineIndexFilter::class, [
            'builder' => $this->getModelQuery(),
            'filterDto' => $dto,
        ]);
    }

}
