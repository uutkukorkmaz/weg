<?php

namespace App\Abstract;

use App\Enums\Http\AuthType;
use App\Enums\Http\Method;
use App\Models\Provider;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

abstract class TaskProvider implements \App\Contracts\TaskProvider
{

    public function __construct(public Provider $provider)
    {
    }

    protected function hasAuth(): bool
    {
        return false;
    }

    protected function getAuthToken(): string
    {
        return '';
    }

    protected function getAuthType(): AuthType
    {
        return AuthType::None;
    }

    abstract protected function getRetrieveMethod(): Method;

    protected function getRetrievePayload(): array
    {
        return [];
    }

    /**
     * **Best Practice**: Keeping base url and the endpoint seperate would be a better approach,
     * but for the cases sake we will just use the url as the endpoint.
     *
     * @return string
     */
    protected function getRetrieveUrl(): string
    {
        return $this->provider->url;
    }

    abstract public function retrieveTasks();

    /**
     * @throws \Exception
     */
    protected function raw(): Response
    {
        $request = Http::acceptJson();

        $method = Str::lower($this->getRetrieveMethod()->value);

        return $this->authorizeRequest($request)->{$method}($this->getRetrieveUrl());
    }


    protected function authorizeRequest(PendingRequest $request): PendingRequest
    {
        if (!$this->hasAuth()) {
            return $request;
        }

        if ($this->getAuthType()->is(AuthType::None)) {
            throw new \Exception('Auth type is not set. Please implement the getAuthType() method.');
        }

        return $request->withToken($this->getAuthToken(), $this->getAuthType()->value);
    }
}