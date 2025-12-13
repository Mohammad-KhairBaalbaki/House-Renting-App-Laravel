<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\House>
 */
class HouseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(),
            'description' => $this->faker->sentence(),
            'rent_value'=> $this->faker->randomFloat(2, 1000, 10000),
            'rooms'=> $this->faker->numberBetween(1, 5),
            'space'=> $this->faker->numberBetween(50, 200),
            'notes'=> $this->faker->sentence(),
            'governorate_id'=> $this->faker->numberBetween(1, 5),
            'city_id'=> $this->faker->numberBetween(1, 5),
            'street'=> $this->faker->streetName(),
            'flat_number'=> $this->faker->buildingNumber(),
            'longitude'=> $this->faker->longitude(),
            'latitude'=> $this->faker->latitude(),
        ];
    }
}
