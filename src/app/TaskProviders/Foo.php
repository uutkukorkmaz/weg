<?php

namespace App\TaskProviders;

use App\Abstract\TaskProvider;
use App\Builders\Task;
use App\Enums\Http\Method;

class Foo extends TaskProvider
{

    protected function getRetrieveMethod(): Method
    {
        return Method::GET;
    }

    public function retrieveTasks(): \Illuminate\Support\Collection
    {
        return $this->raw()
            ->collect()
            ->collapse()
            ->map(function ($task, $taskName) {
                return (new Task($this->provider))
                    ->setName($taskName)
                    ->setDifficulty($task['level'])
                    ->setEstimatedDurationInHours($task['estimated_duration'])
                    ->toArray();
            })->values();
    }
}