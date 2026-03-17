<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $students = [
            [
                'student_id' => 'STU001',
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'phone' => '1234567890',
                'status' => 'active',
            ],
            [
                'student_id' => 'STU002',
                'name' => 'Jane Smith',
                'email' => 'jane@example.com',
                'phone' => '0987654321',
                'status' => 'active',
            ],
        ];

        foreach ($students as $student) {
            \App\Models\Student::updateOrCreate(['student_id' => $student['student_id']], $student);
        }
    }
}
