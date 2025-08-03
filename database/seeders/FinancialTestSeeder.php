<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\InMoney;
use App\Models\UserMoney;

class FinancialTestSeeder extends Seeder
{
    public function run()
    {
        // Create test users if they don't exist
        $customer1 = User::firstOrCreate(
            ['phone' => '1111111111'],
            [
                'name' => 'Test Customer 1',
                'email' => 'customer1@test.com',
                'password' => bcrypt('password'),
                'role' => 'customer',
                'is_verified' => true,
            ]
        );

        $customer2 = User::firstOrCreate(
            ['phone' => '2222222222'],
            [
                'name' => 'Test Customer 2',
                'email' => 'customer2@test.com',
                'password' => bcrypt('password'),
                'role' => 'customer',
                'is_verified' => true,
            ]
        );

        // Create test deposit requests
        $deposits = [
            [
                'user_id' => $customer1->id,
                'money' => 50.00,
                'status' => 'pending',
                'type' => 'bank_transfer',
                'image' => null,
            ],
            [
                'user_id' => $customer2->id,
                'money' => 100.00,
                'status' => 'pending',
                'type' => 'mobile_payment',
                'image' => null,
            ],
            [
                'user_id' => $customer1->id,
                'money' => 25.00,
                'status' => 'approved',
                'type' => 'cash',
                'image' => null,
            ],
            [
                'user_id' => $customer2->id,
                'money' => 75.00,
                'status' => 'rejected',
                'type' => 'bank_transfer',
                'image' => null,
                'rejection_reason' => 'Invalid payment proof',
            ],
        ];

        foreach ($deposits as $depositData) {
            InMoney::firstOrCreate(
                [
                    'user_id' => $depositData['user_id'],
                    'money' => $depositData['money'],
                    'status' => $depositData['status']
                ],
                $depositData
            );
        }

        // Create user money records for approved deposits
        UserMoney::firstOrCreate(
            ['user_id' => $customer1->id],
            ['money' => 25.00]
        );

        $this->command->info('Financial test data seeded successfully!');
        $this->command->info('Created test customers and deposit requests');
        $this->command->info('- 2 pending deposits for testing approval');
        $this->command->info('- 1 approved deposit');
        $this->command->info('- 1 rejected deposit');
    }
}