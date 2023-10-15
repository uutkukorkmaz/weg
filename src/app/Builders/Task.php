<?php

namespace App\Builders;

use App\Models\Provider;
use Illuminate\Contracts\Support\Arrayable;

class Task implements Arrayable
{

    public function __construct(
        protected Provider $provider,
        protected ?string $name = null,
        protected ?int $difficulty = null,
        protected ?int $estimatedDurationInHours = null,
    )
    {
    }

    public function setName(?string $name): self
    {
        $this->name = $name;
        return $this;
    }



    public function setDifficulty(?int $difficulty): self
    {
        $this->difficulty = $difficulty;
        return $this;
    }

    public function setEstimatedDurationInHours(?int $estimatedDurationInHours): self
    {
        $this->estimatedDurationInHours = $estimatedDurationInHours;
        return $this;
    }

    public function getProvider(): Provider
    {
        return $this->provider;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getDifficulty(): ?int
    {
        return $this->difficulty;
    }

    public function getEstimatedDurationInHours(): ?int
    {
        return $this->estimatedDurationInHours;
    }

    public function toArray()
    {
        return [
            'name' => $this->getName(),
            'provider_id' => $this->getProvider()->id,
            'difficulty' => $this->getDifficulty(),
            'estimated_duration_in_hours' => $this->getEstimatedDurationInHours(),
        ];
    }
}