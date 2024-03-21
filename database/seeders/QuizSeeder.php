<?php

namespace Database\Seeders;

use App\Models\Quiz;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class QuizSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $quizzes = [
            [
                'subject_id' => 1,
                'time_limit' => 20, // in minutes
                'start_time' => now()->addHours(1), // Starts in 1 hour from now
                'end_time' => now()->addHours(2), // Ends in 2 hours from now
                'no_of_questions' => 30,
                'instructions' => 'Answer all questions within the time limit.',
            ],
            [
                'subject_id' => 6,
                'time_limit' => 30, // in minutes
                'start_time' => now()->addHours(4)->addMinutes(30),
                'end_time' => now()->addHours(3), // Ends in 3 hours from now
                'no_of_questions' => 60,
                'instructions' => 'Answer all questions carefully.',
            ],
            [
                'subject_id' => 5,
                'time_limit' => 30, // in minutes
                'start_time' => now()->addHours(2)->addMinutes(30),
                'end_time' => now()->addHours(3), // Ends in 3 hours from now
                'no_of_questions' => 20,
                'instructions' => 'Answer all questions carefully.',
            ]
        ];

        foreach ($quizzes as $quizData) {
            Quiz::create($quizData);
        }
    }
}
