<?php

namespace App\Contracts;

use Illuminate\Support\Collection;

interface TaskProvider
{
    public function retrieveTasks(): Collection;
}