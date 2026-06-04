<?php

namespace Database\Factories;

use App\Models\Governorate;
use Illuminate\Database\Eloquent\Factories\Factory;

class CityFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->city(),
            'governorate_id' => Governorate::factory()->create()->id,
            'shipping_cost' => fake()->randomDigit(),

        ];
    }
}