<?php

namespace App\Strategies;

use App\Abstract\AssignStrategy;
use App\Models\Task;
use Illuminate\Support\Collection;

class RandomStrategy extends AssignStrategy
{

    protected function getTasks(): Collection
    {
        return Task::inRandomOrder()->get();
    }

}