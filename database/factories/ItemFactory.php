<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Item>
 */
class ItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'iid' => 'IM',
            'item_number' => $this->faker->randomElements(['BFE528814', 'B45A50272', 'B45A70571']),
            'item_description' => $this->faker->sentence,
        ];
    }
}
