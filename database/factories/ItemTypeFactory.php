<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ItemType>
 */
class ItemTypeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'key' => $this->faker->randomElement([1, 2, 3, 4, 5]),
            'data' => $this->faker->word,
            'status' => $this->faker->randomElement(['IM', 'IZ']),
        ];
    }
}
