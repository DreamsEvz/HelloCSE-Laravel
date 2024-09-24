<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Profile>
 */
class ProfileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
           'firstname' => fake()->unique()->firstName(),
            'lastname' => fake()->unique()->lastName(),
            //Génération de path d'image aléatoire
            'picture' => 'public/images/' . fake()->words(1, true) . '.jpg',
            'status' => fake()->randomElement(['inactive', 'waiting', 'active']),
            'created_at' => now(),
        ];
    }
}
