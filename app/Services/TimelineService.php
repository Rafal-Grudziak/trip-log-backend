<?php

namespace App\Services;

use App\Dtos\TimelineIndexDto;
use App\Filters\BaseIndexFilter;
use App\Filters\TimelineIndexFilter;
use App\Models\Travel;

class TimelineService extends ModelService
{

    protected function getModelClass(): string
    {
        return Travel::class;
    }

    public function filter(TimelineIndexDto $dto): BaseIndexFilter
    {
        return app()->make(TimelineIndexFilter::class, [
            'builder' => $this->getModelQuery(),
            'filterDto' => $dto,
        ]);
    }

}
