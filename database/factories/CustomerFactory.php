<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Customer>
 */
class CustomerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory()->create(),
            'name' => fake()->name,
            'document' => fake()->cpf,
            'birthdate' => now()->subYears(25)->format('Y-m-d'),
            'email' => fake()->safeEmail,
            'mobile' => '(16) ' . fake()->cellphone,
        ];
    }
}
