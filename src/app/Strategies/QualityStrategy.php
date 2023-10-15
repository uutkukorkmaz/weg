<?php

namespace App\Strategies;

use App\Abstract\AssignStrategy;
use App\Contracts\Manager;
use App\Enums\Sorting\Direction;

class QualityStrategy extends AssignStrategy
{

    protected function getTaskSorterKey(): string
    {
        return 'estimated_duration_in_hours';
    }

    protected function getTaskSortingDirection(): Direction
    {
        return Direction::Descending;
    }

    protected function skipTooDifficultTasks(): bool
    {
        return false;
    }

    protected function getComplexityBuffer(): int
    {
        return -1;
    }

    protected function getDeveloperSortingDirection(): Direction
    {
        return Direction::Descending;
    }

}