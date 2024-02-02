<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\PanelTypeEnum;
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
