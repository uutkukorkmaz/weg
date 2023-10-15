<?php

namespace App\Abstract;

use App\Contracts\Manager;
use App\Contracts\Strategy;
use App\Enums\Sorting\Direction;
use App\Models\Developer;
use App\Models\Task;
use Illuminate\Support\Collection;

abstract class AssignStrategy implements Strategy
{
    protected function getTasks(): Collection
    {
        return Task::all();
    }

    protected function getDevelopers(): Collection
    {
        return Developer::select('id', 'seniority')->get();
    }

    public function developers()
    {
        return $this->getDevelopers()->sortBy(
            $this->getDeveloperSorterKey(),
            descending: $this->getDeveloperSortingDirection()->is(Direction::Descending)
        );
    }

    public function tasks()
    {
        return $this->getTasks()->sortBy(
            $this->getTaskSorterKey(),
            descending: $this->getTaskSortingDirection()->is(Direction::Descending)
        );
    }

    protected function getDeveloperSorterKey(): string
    {
        return 'seniority';
    }

    protected function getDeveloperSortingDirection(): Direction
    {
        return Direction::Ascending;
    }

    protected function getTaskSorterKey(): string
    {
        return 'difficulty';
    }

    protected function getTaskSortingDirection(): Direction
    {
        return Direction::Ascending;
    }

    protected function skipTooDifficultTasks(): bool
    {
        return true;
    }

    protected function skipTooSimpleTasks(): bool
    {
        return true;
    }

    protected function getSimplicityBuffer(): int
    {
        return 0;
    }

    protected function getComplexityBuffer(): int
    {
        return 0;
    }

    protected function isTooSimple(Task $task, Developer $developer): bool
    {
        return $task->difficulty < $developer->seniority + $this->getSimplicityBuffer();
    }

    protected function isTooDifficult(Task $task, Developer $developer): bool
    {
        return $task->difficulty > $developer->seniority + $this->getComplexityBuffer();
    }

    public function execute(Manager $manager): Manager
    {
        $this->developers()->each(function (Developer $developer) use ($manager) {
            $this->tasks()->each(function (Task $task) use ($manager, $developer) {
                if ($this->skipTooDifficultTasks() && $this->isTooDifficult($task, $developer)) {
                    return;
                }

                if ($this->skipTooSimpleTasks() && $this->isTooSimple($task, $developer)) {
                    return;
                }

                $manager->assign($task, $developer);
            });
        });

        return $manager;
    }

}