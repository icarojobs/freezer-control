<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Customer::factory()->create([
            'name' => 'Administrator',
            'email' => 'admin@admin.com',
            'document' => fake()->cpf,
            'birthdate' => now()->subYears(25)->format('Y-m-d'),
            'mobile' => '(16) '.fake()->cellphone,
        ]);

        Customer::factory()->create([
            'name' => fake()->unique()->name,
            'email' => fake()->unique()->safeEmail,
            'document' => fake()->cpf,
            'birthdate' => now()->subYears(25)->format('Y-m-d'),
            'mobile' => '(16) '.fake()->cellphone,
        ]);
    }
}
