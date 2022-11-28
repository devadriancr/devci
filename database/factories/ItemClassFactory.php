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
            'iid' => $this->faker->randomElements(['IM', 'IC', 'IZ']),
            'code' => $this->faker->word,
            'name' => $this->faker->sentence
        ];
    }
}
