<?php

namespace App\TaskProviders;

use App\Abstract\TaskProvider;
use App\Builders\Task;
use App\Enums\Http\Method;

class Bar extends TaskProvider
{

    protected function getRetrieveMethod(): Method
    {
        return Method::GET;
    }

    public function retrieveTasks(): \Illuminate\Support\Collection
    {
        return $this->raw()
            ->collect()
            ->map(function ($task) {
                return (new Task($this->provider))
                    ->setName($task['id'])
                    ->setDifficulty($task['zorluk'])
                    ->setEstimatedDurationInHours($task['sure'])
                    ->toArray();
            })->values();
    }
}