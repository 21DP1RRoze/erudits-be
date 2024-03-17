<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Answer;
use App\Models\Question;
use App\Models\QuestionGroup;
use App\Models\Quiz;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // Creates a test user
        User::factory()->create([
            'username' => 'admin',
            'password' => bcrypt('admin'),
        ]);

        // For each user creates 2 quizzes
        User::all()->each(function ($user) {
            $user->quizzes()->saveMany(
                Quiz::factory(2)->make()
            );
        });

        // For each quiz creates 3 question groups
        Quiz::all()->each(function ($quiz) {
            $quiz->questionGroups()->saveMany(
                QuestionGroup::factory(3)->make()
            );
        });

        // For each question group creates 5 questions
        QuestionGroup::all()->each(function ($questionGroup) {
            $questionGroup->questions()->saveMany(
                Question::factory(5)->make()
            );
        });

        // For each question creates 4 answers
        Question::all()->each(function ($question) {
            $question->answers()->saveMany(
                Answer::factory(4)->make()
            );
        });
    }
}
