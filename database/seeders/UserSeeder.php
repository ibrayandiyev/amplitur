<?php

namespace Database\Seeders;

use App\Enums\AccessStatus;
use App\Enums\UserType;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::factory()->create([
            'name' => 'Master',
            'username' => 'master',
            'email' => 'master@amplitur.com.br',
            'is_active' => true,
            'type' => UserType::MASTER,
            'status' => AccessStatus::ACTIVE,
        ]);

        User::factory()->create([
            'name' => 'Manager',
            'username' => 'manager',
            'email' => 'manager@amplitur.com.br',
            'is_active' => true,
            'type' => UserType::MANAGER,
            'status' => AccessStatus::ACTIVE,
        ]);
    }
}
