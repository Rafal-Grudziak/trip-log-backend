<?php

namespace App\Filters;

class TimelineIndexFilter extends BaseIndexFilter
{
    public function filter(): void
    {
        $this->filterOnlyFriendsTravels();
        $this->filterByDateRange();

        $this->orderBy('created_at', $this->filterDto->sort_direction ?? 'desc');
    }

    protected function filterOnlyFriendsTravels(): void
    {
        $user = auth()->user();
        $friendIds = $user->friends()->pluck('id');

        $this->builder->whereIn('user_id', $friendIds);
    }

    protected function filterByDateRange(): void
    {
        if ($this->filterDto->date_from) {
            $this->builder->where('from', '>=', $this->filterDto->date_from);
        }

        if ($this->filterDto->date_to) {
            $this->builder->where('to', '<=', $this->filterDto->date_to);
        }
    }
}
