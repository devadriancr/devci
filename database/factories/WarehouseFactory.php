<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Warehouse>
 */
class WarehouseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'lid' => $this->faker->randomElement(['WM, WZ']),
            'code' => $this->faker->randomLetter . $this->faker->randomDigit . $this->faker->randomDigit,
            'name' => $this->faker->word
        ];
    }
}
