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
        $faker = \Faker\Factory::create('pt_BR');

        $users = User::factory(20)->create();

        $users->each(function (User $user) use ($faker) {
            Customer::factory()->create([
                'user_id' => $user->id,
                'name' => $faker->name,
                'document' => $faker->cpf,
                'birthdate' => now()->subYears(25)->format('Y-m-d'),
                'email' => $faker->safeEmail,
                'mobile' => '(16) ' . $faker->cellphone,
            ]);
        });
    }
}
