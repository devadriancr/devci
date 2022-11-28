<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Location>
 */
class LocationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'wid' => $this->faker->randomLetter . $this->faker->randomLetter,
            'code' => $this->faker->randomLetter . $this->faker->randomDigit . $this->faker->randomDigit,
            'name' => $this->faker->word,
        ];
    }
}
