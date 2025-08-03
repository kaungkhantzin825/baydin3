<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            [
                'name' => 'Love & Relationships',
                'price' => 25.00,
                'description' => 'Get insights about your romantic life, relationships, and love compatibility.',
                'status_new' => 'active',
            ],
            [
                'name' => 'Career & Finance',
                'price' => 30.00,
                'description' => 'Discover your career path, financial opportunities, and professional growth.',
                'status_new' => 'active',
            ],
            [
                'name' => 'Health & Wellness',
                'price' => 20.00,
                'description' => 'Learn about your health, wellness, and lifestyle recommendations.',
                'status_new' => 'active',
            ],
            [
                'name' => 'Family & Children',
                'price' => 22.00,
                'description' => 'Guidance on family matters, parenting, and children-related concerns.',
                'status_new' => 'active',
            ],
            [
                'name' => 'Spiritual Growth',
                'price' => 35.00,
                'description' => 'Explore your spiritual journey, meditation, and personal development.',
                'status_new' => 'active',
            ],
            [
                'name' => 'General Reading',
                'price' => 15.00,
                'description' => 'A comprehensive reading covering all aspects of your life.',
                'status_new' => 'active',
            ],
        ];

        foreach ($categories as $categoryData) {
            Category::updateOrCreate(
                ['name' => $categoryData['name']],
                $categoryData
            );
        }

        $this->command->info('Categories seeded successfully!');
    }
}