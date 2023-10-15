<?php

namespace App\Factories;

use App\Contracts\TaskProvider;
use App\Models\Provider;
use Exception;

class ProviderFactory
{

    /**
     * @throws Exception
     */
    public static function make(Provider $provider): TaskProvider
    {
        $providerClass = $provider->resolver;

        if (!class_exists($providerClass)) {
            throw new Exception("Provider class {$providerClass} does not exist.");
        }

        try {
            $instance = new $providerClass($provider);
        } catch (\Throwable $e) {
            throw new \RuntimeException($e->getMessage(), $e->getCode());
        }

        if ($instance instanceof TaskProvider) {
            return $instance;
        }

        throw new Exception("Provider class {$providerClass} does not implement TaskProvider interface.");
    }

}