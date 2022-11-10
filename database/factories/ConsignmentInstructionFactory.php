<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ConsignmentInstruction>
 */
class ConsignmentInstructionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'serial' => $this->faker->randomDigit . $this->faker->randomLetter .  $this->faker->randomDigit . $this->faker->randomLetter .  $this->faker->randomDigit . $this->faker->randomLetter,
            'container_id' => $this->faker->randomElement([1, 2, 3, 4, 5]),
        ];
    }
}
