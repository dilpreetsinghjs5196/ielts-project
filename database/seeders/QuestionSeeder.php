<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class QuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $listening = \App\Models\Category::where('slug', 'listening')->first();
        $academic = \App\Models\TestType::where('slug', 'academic')->first();
        $general = \App\Models\TestType::where('slug', 'general-training')->first();
        $level1 = \App\Models\Level::where('slug', 'level-1-and-2')->first();

        if ($listening && $academic && $level1) {
            \App\Models\Question::create([
                'category_id' => $listening->id,
                'test_type_id' => $academic->id,
                'level_id' => $level1->id,
                'question_type' => 'mcq',
                'title' => 'Listening Section 1: Library Enrollment',
                'content' => 'Listen to the audio and answer questions 1-5. What is the main reason the student is visiting the library?',
                'options' => ['Research for essay', 'Renew library card', 'Buy textbooks', 'Volunteer work'],
                'correct_answer' => 'Renew library card',
                'explanation' => 'The student explicitly mentions their card expired last week.',
                'marks' => 1,
                'status' => 'active'
            ]);
        }

        $reading = \App\Models\Category::where('slug', 'reading')->first();
        if ($reading && $academic && $level1) {
            \App\Models\Question::create([
                'category_id' => $reading->id,
                'test_type_id' => $academic->id,
                'level_id' => $level1->id,
                'question_type' => 'tfng',
                'title' => 'Reading Passage: The History of Tea',
                'passage' => 'Tea cultivation began in East Asia over 5,000 years ago. It was initially used for medicinal purposes before becoming a popular beverage. By the 17th century, it had reached Europe...',
                'content' => 'Questions 1-6: True, False, or Not Given. 1. Tea was always consumed as a luxury drink.',
                'correct_answer' => 'False',
                'explanation' => 'The text says it was initially used for medicine, not as a luxury drink.',
                'marks' => 1,
                'status' => 'active'
            ]);
        }

        $writing = \App\Models\Category::where('slug', 'writing')->first();
        if ($writing && $academic && $level1) {
            \App\Models\Question::create([
                'category_id' => $writing->id,
                'test_type_id' => $academic->id,
                'level_id' => $level1->id,
                'question_type' => 'essay',
                'title' => 'Writing Task 2: Technology in Schools',
                'content' => 'Some people believe that computers will replace teachers in the future. To what extent do you agree or disagree?',
                'correct_answer' => 'Sample Essay: While technology is a powerful tool...',
                'explanation' => 'Focus on balancing both sides of the argument.',
                'marks' => 9,
                'status' => 'active'
            ]);
        }
    }
}
