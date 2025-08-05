<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        User::updateOrCreate(
            ['phone' => '1234567890'],
            [
                'name' => 'Admin User',
                'email' => 'admin@astrology.com',
                'phone' => '1234567890',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'is_verified' => true,
            ]
        );

        $this->command->info('Admin user created successfully!');
        $this->command->info('Phone: 1234567890');
        $this->command->info('Password: admin123');
    }
}
