<?php

namespace App\TaskProviders;

use App\Abstract\TaskProvider;
use App\Enums\Http\Method;

class Bar extends TaskProvider
{

    protected function getRetrieveMethod(): Method
    {
        return Method::GET;
    }

    public function retrieveTasks()
    {
        //
    }
}