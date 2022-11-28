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
            'code' => $this->faker->randomLetter . $this->faker->randomLetter . $this->faker->randomDigit .  $this->faker->randomDigit . $this->faker->randomLetter . $this->faker->randomLetter,
            'arrival_date' => Carbon::now()->format('Ymd'),
            'arrival_time' => Carbon::now()->addHour()->format('His'),
        ];
    }
}
