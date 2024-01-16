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
        $users = User::factory(20)->create();

        $users->each(function (User $user) {
            Customer::factory()->create([
                'user_id' => $user->id,
                'name' => fake()->name,
                'document' => fake()->cpf,
                'birthdate' => now()->subYears(25)->format('Y-m-d'),
                'email' => fake()->safeEmail,
                'mobile' => '(16) ' . fake()->cellphone,
            ]);
        });
    }
}
