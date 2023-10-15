<?php

namespace App\Strategies;

use App\Abstract\AssignStrategy;
use App\Enums\Sorting\Direction;

class FastDeliveryStrategy extends AssignStrategy
{

    protected function getTaskSorterKey(): string
    {
        return 'estimated_duration_in_hours';
    }

    protected function getDeveloperSortingDirection(): Direction
    {
        return Direction::Descending;
    }

}