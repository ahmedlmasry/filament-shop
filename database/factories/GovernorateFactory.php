<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class GovernorateFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->state(),
        ];
    }
}