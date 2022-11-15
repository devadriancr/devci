<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MeasurementUnit>
 */
class MeasurementUnitFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'unit' => $this->faker->randomLetter() . $this->faker->randomLetter(),
            'description' => $this->faker->sentence,
            'status' => $this->faker->randomElement(['IM', 'IZ']),
        ];
    }
}
