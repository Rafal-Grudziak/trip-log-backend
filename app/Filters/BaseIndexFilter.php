<?php

namespace App\Filters;

use App\Dtos\FilterDto;
use Illuminate\Database\Eloquent\Builder;

abstract class BaseIndexFilter
{
    public function __construct(
        protected Builder $builder,
        protected FilterDto $filterDto
    ) {}

    abstract public function filter(): void;

    public function getBuilder(): Builder
    {
        $this->filter();
        return $this->builder;
    }

    protected function orderBy(string $column, string $direction = 'asc'): void
    {
        $this->builder->orderBy($column, $direction);
    }

    public function paginate(int $perPage = 15)
    {
        return $this->getBuilder()->paginate($perPage);
    }

}
