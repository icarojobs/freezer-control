<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Customer::factory(20)->create([
            'name' => fake()->name,
            'document' => fake()->cpf,
            'birthdate' => now()->subYears(25)->format('Y-m-d'),
            'mobile' => '(16) ' . fake()->cellphone,
        ]);
    }
}
