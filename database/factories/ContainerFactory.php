<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Container>
 */
class ContainerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'code' => $this->faker->word() . $this->faker->word(),
            'date' => Carbon::now()->toDateString(),
            'time' => Carbon::now()->toTimeString(),
        ];
    }
}
