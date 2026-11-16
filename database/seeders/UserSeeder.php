<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['username' => 'emmanuel'],
            ['name' => 'Emmanuel', 'password' => '12345', 'role' => User::ROLE_ADMIN, 'is_active' => true]
        );

        User::updateOrCreate(
            ['username' => 'editor'],
            ['name' => 'Editor', 'password' => '12345', 'role' => User::ROLE_EDITOR, 'is_active' => true]
        );
    }
}