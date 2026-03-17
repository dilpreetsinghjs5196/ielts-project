<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Listening',
                'slug' => 'listening',
                'icon' => 'fas fa-headphones',
                'description' => 'IELTS Listening module tests your ability to understand main ideas and detailed factual information.'
            ],
            [
                'name' => 'Reading',
                'slug' => 'reading',
                'icon' => 'fas fa-book-open',
                'description' => 'IELTS Reading module includes three long texts ranging from descriptive and factual to discursive and analytical.'
            ],
            [
                'name' => 'Writing',
                'slug' => 'writing',
                'icon' => 'fas fa-pen-nib',
                'description' => 'IELTS Writing module includes two tasks: a descriptive report and a discursive essay.'
            ],
            [
                'name' => 'Speaking',
                'slug' => 'speaking',
                'icon' => 'fas fa-comments',
                'description' => 'IELTS Speaking module is a face-to-face interview with an examiner.'
            ],
        ];

        foreach ($categories as $category) {
            \App\Models\Category::updateOrCreate(['slug' => $category['slug']], $category);
        }
    }
}
