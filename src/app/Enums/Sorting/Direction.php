<?php

namespace App\Enums\Sorting;

use App\Concerns\ComparesInstances;

enum Direction: string
{
    use ComparesInstances;

    case Ascending = 'asc';
    case Descending = 'desc';
}