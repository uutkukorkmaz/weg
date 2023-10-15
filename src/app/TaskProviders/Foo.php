<?php

namespace App\TaskProviders;

use App\Abstract\TaskProvider;
use App\Enums\Http\Method;

class Foo extends TaskProvider
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