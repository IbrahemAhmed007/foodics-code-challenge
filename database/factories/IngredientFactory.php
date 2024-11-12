<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ingredient>
 */
class IngredientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $availableQuantity = $this->faker->numberBetween(100, 10000);
        return [
            'name' => $this->faker->name(),
            'available_quantity' => $availableQuantity,
            'alert_quantity' => $availableQuantity / 2,
        ];
    }
}
