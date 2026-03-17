<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TestTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            [
                'name' => 'Academic',
                'slug' => 'academic',
                'description' => 'The IELTS Academic test is for people applying for higher education or professional registration in an English speaking environment.'
            ],
            [
                'name' => 'General Training',
                'slug' => 'general-training',
                'description' => 'The IELTS General Training test is for those who are going to English speaking countries for secondary education, work experience or training programs.'
            ],
        ];

        foreach ($types as $type) {
            \App\Models\TestType::updateOrCreate(['slug' => $type['slug']], $type);
        }
    }
}
