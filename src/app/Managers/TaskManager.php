<?php

namespace App\Managers;

use App\Abstract\AssignStrategy;
use App\Contracts\Manager;
use App\Models\Developer;
use App\Models\Task;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class TaskManager implements Manager
{

    protected AssignStrategy $strategy;

    protected Collection $plan;

    protected Collection $assigned;

    public function __construct()
    {
        $this->plan = new Collection();
        $this->assigned = new Collection();
    }

    public function assign(Task $task, Developer $developer): void
    {
        if (!$this->hasPlan($developer)) {
            $this->initPlan($developer);
        }

        if ($this->assigned->contains($task->id)) {
            return;
        }

        $task->effort = $this->getEffort($task, $developer);
        $this->getFirstAvailableWeek($task, $developer)->push($task);
        $this->assigned->push($task->id);
    }

    protected function initPlan(Developer $developer)
    {
        $this->plan->put($developer->id, new Collection([new Collection()]));


        return $this;
    }

    protected function hasPlan(Developer $developer): bool
    {
        return $this->plan->has($developer->id);
    }

    public function getEffort(Task $task, Developer $developer): float
    {
        return ($task->estimated_duration_in_hours * $task->difficulty) / $developer->seniority;
    }

    public function using(AssignStrategy $strategy)
    {
        $this->strategy = $strategy;
        return $this;
    }

    public function massAssignUsing(AssignStrategy|string $strategy)
    {
        if (is_string($strategy)) {
            if (!class_exists($strategy)) {
                throw new \Exception("Class $strategy does not exist");
            }

            $strategy = app($strategy);
        }

        return $this->using($strategy)->massAssign();
    }

    public function massAssign()
    {
        $this->strategy->execute($this);
        return $this;
    }

    protected function getFirstAvailableWeek(Task $incoming_task, Developer $developer): Collection
    {
        $availableWeek = $this->getPlan($developer)
            ->reject(function ($week) use ($incoming_task, $developer) {
                $totalEffortInWeek = $week->sum(fn($existing_task) => $this->getEffort($existing_task, $developer));
                $incomingEffort = $this->getEffort($incoming_task, $developer);

                return ($totalEffortInWeek + $incomingEffort) > $developer->getWorkingHours();
            })->first();

        if (is_null($availableWeek)) {
            $this->newWeekFor($developer);
            return $this->getPlan($developer)->last();
        }

        return $availableWeek;
    }

    public function getPlan(?Developer $developer = null): Collection
    {
        if (!is_null($developer)) {
            return $this->plan->get($developer->id);
        }

        return $this->plan;
    }

    public function getAllStrategies()
    {
        $path = app_path();
        $files = glob(app_path().'/*/*.php');
        foreach ($files as $file) {
            $fqcn = 'App'.Str::replace(['.php', '/'], ['', '\\'], Str::after($file, $path));
            if (class_exists($fqcn) && is_subclass_of($fqcn, AssignStrategy::class)) {
                $strategies[] = $fqcn;
            }
        }

        return $strategies;
    }

    protected function newWeekFor(Developer $developer)
    {
        $this->plan->get($developer->id)->push(new Collection());
    }
}