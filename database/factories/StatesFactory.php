<?php

namespace Database\Factories;

use App\Models\Countries;
use App\Models\States;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\states>
 */
class StatesFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = States::class;
    public function definition(): array
    {
        return [
            //
            "name" => fake()->state(),
            "population" => fake()->numberBetween(100, 10000),
            "country_id" => function () {
                return Countries::factory()->create()->id;
            }
        ];
    }
}
