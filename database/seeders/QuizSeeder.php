<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Quiz;

class QuizSeeder extends Seeder
{
    public function run()
    {
        $json = file_get_contents(database_path('quiz_seed.json'));
        $quizzes = json_decode($json, true);

        foreach ($quizzes as $quiz) {
            Quiz::create($quiz);
        }
    }
}