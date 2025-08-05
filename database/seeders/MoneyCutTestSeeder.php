<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\UserMoney;
use App\Models\Category;

class MoneyCutTestSeeder extends Seeder
{
    public function run()
    {
        // Create test user with sufficient money
        $richUser = User::firstOrCreate(
            ['phone' => '9999999999'],
            [
                'name' => 'Rich Test User',
                'email' => 'rich@test.com',
                'password' => bcrypt('password'),
                'role' => 'customer',
                'is_verified' => true,
            ]
        );

        // Create test user with insufficient money
        $poorUser = User::firstOrCreate(
            ['phone' => '8888888888'],
            [
                'name' => 'Poor Test User',
                'email' => 'poor@test.com',
                'password' => bcrypt('password'),
                'role' => 'customer',
                'is_verified' => true,
            ]
        );

        // Give rich user plenty of money
        UserMoney::updateOrCreate(
            ['user_id' => $richUser->id],
            ['money' => 10000.00]
        );

        // Give poor user insufficient money
        UserMoney::updateOrCreate(
            ['user_id' => $poorUser->id],
            ['money' => 100.00]
        );

        $this->command->info('Money cut test data created successfully!');
        $this->command->info('Rich User (phone: 9999999999) - Balance: $10,000');
        $this->command->info('Poor User (phone: 8888888888) - Balance: $100');
        $this->command->info('Categories available with prices from $15 to $35');
    }
}