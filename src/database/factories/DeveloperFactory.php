<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Developer>
 */
class DeveloperFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
        ];
    }

    /**
     * Indication for the developer's seniority
     *
     * @param  int  $seniority
     * @return Factory
     */
    public function seniority(int $seniority = 1): Factory
    {
        return $this->state(fn() => [
            'seniority' => $seniority,
        ]);
    }
}
