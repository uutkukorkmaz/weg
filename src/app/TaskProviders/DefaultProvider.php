<?php

namespace App\TaskProviders;

use App\Abstract\TaskProvider;
use app\Enums\Http\AuthType;
use app\Enums\Http\Method;

class DefaultProvider extends TaskProvider
{

    protected function getRetrieveMethod(): Method
    {
        return Method::GET;
    }

    public function retrieveTasks(): \Illuminate\Support\Collection
    {
        return collect();
    }

}