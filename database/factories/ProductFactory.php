<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        return [
            'type' => $this->faker->word(),
            'name' => $this->faker->word(),
            'price' => $this->faker->randomFloat(),
            'sku' => $this->faker->randomLetter(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
