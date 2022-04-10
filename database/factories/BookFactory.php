<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class BookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->catchPhrase(),
            'isbn' => $this->faker->isbn13(),
            'number_of_pages' => $this->faker->numberBetween(103, 999),
            'publisher' => $this->faker->company(),
            'country' => $this->faker->country(),
            'release_date' => $this->faker->date('Y-m-d'),
        ];
    }
}
