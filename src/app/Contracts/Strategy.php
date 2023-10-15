<?php

namespace App\Contracts;

interface Strategy
{

    public function execute(Manager $manager): Manager;

}