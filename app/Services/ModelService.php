<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

abstract class ModelService
{
    abstract protected function getModelClass(): string;

    protected function getModel(): Model
    {
        $modelClass = $this->getModelClass();
        return app($modelClass);
    }

    public function getModelQuery(): Builder
    {
        return app($this->getModelClass())->newQuery();
    }
}
