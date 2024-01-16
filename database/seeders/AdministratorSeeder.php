<?php

namespace Database\Seeders;

use App\Enums\PanelTypeEnum;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdministratorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\User::factory()->create([
            'name' => 'Administrador',
            'email' => 'admin@admin.com',
            'panel' => PanelTypeEnum::ADMIN,
            'password' => bcrypt('password'),
        ]);
    }
}
