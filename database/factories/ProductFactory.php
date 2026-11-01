<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Factory for creating dummy Product records in tests.
 */
class ProductFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name'        => $this->faker->words(3, true),
            'description' => $this->faker->sentence(),
            'price'       => $this->faker->randomFloat(2, 1, 999),
            'qty'         => $this->faker->numberBetween(1, 100),
            'image'       => null,  // no image by default; override in specific tests
        ];
    }
}
