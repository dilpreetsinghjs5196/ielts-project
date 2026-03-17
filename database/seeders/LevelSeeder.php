<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $levels = [
            [
                'name' => 'Level 1 and 2',
                'slug' => 'level-1-and-2',
                'description' => 'Foundation levels for early-stage IELTS preparation.'
            ],
            [
                'name' => 'Level 3',
                'slug' => 'level-3',
                'description' => 'Intermediate level focusing on core IELTS skills and techniques.'
            ],
            [
                'name' => 'Exam Batch',
                'slug' => 'exam-batch',
                'description' => 'Advanced intensive preparation for students approaching their exam date.'
            ],
        ];

        foreach ($levels as $level) {
            \App\Models\Level::updateOrCreate(['slug' => $level['slug']], $level);
        }
    }
}
