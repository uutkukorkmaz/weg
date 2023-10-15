<?php

namespace App\Contracts;

use App\Abstract\AssignStrategy;
use App\Models\Developer;
use App\Models\Task;

interface Manager
{

    public function assign(Task $task, Developer $developer): void;

    public function getEffort(Task $task, Developer $developer): float;

    public function using(AssignStrategy $strategy);

    public function massAssign();

}