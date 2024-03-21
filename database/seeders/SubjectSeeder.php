<?php

namespace Database\Seeders;

use App\Models\Subject;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $users = User::all();

        $subjects = [
            [
                'name' => 'ENG 224',
                'co_lecturer' => 'Dr Shay',
            ],

            [
                'name' => 'MAT 110',
                'co_lecturer' => 'Dr Kelvin',
            ]
        ];

        foreach ($subjects as $subjectData) {
            $subject = Subject::create($subjectData);
    
            // Attach subjects to users
            $users->each(function ($user) use ($subject) {
                $user->subjects()->attach($subject);
            });
        }
    }
}
