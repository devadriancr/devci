<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ItemClass>
 */
class ItemClassFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'code' => $this->faker->randomLetter() . $this->faker->randomLetter(),
            'description' => $this->faker->sentence,
            'status' => $this->faker->randomElement(['IM', 'IC', 'IZ']),
        ];
    }
}
