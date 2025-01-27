<?php

namespace App\Observers;

use App\Models\Travel;

class TravelObserver
{
    public function saving(Travel $travel): void
    {
        if ($travel->to < $travel->from) {
            throw new \InvalidArgumentException('End date must be after start date');
        }
    }
}
