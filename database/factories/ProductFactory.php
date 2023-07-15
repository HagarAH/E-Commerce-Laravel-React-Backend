<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $burgerNouns = ['Beef', 'Chicken', 'Veggie', 'Cheese', 'Double', 'Grilled', 'Deluxe', 'Bacon', 'Spicy', 'Classic']; // define your burger nouns here

        $selectedNouns = $this->faker->randomElements($burgerNouns, 3); // This will select three random elements

        $burgerName = implode(" ", $selectedNouns); // This will combine the three selected words into a single string with spaces in between

        return [
            'name' => $burgerName,
            'price' => $this->faker->numberBetween(10, 20),
            'description'=>$this->faker->Text(255),
            ];
    }
}
