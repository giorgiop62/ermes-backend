<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller;
use App\Models\Quiz;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    public function index()
    {
        $quizzes = Quiz::all();

        // Manda i dati al frontend in JSON
        return response()->json($quizzes);
    }

    public function store(Request $request)
{
    \Log::info('Dati ricevuti', $request->all());

    $this->validate($request, [
        'question' => 'required|string',
        'option_a' => 'required|string',
        'option_b' => 'required|string',
        'option_c' => 'required|string',
        'option_d' => 'required|string',
        'correct_option' => 'required|in:A,B,C,D',
        'correct_option' => strtoupper($request->input('correct_option')),

    ]);

    $quiz = Quiz::create($request->only([
        'question', 'option_a', 'option_b', 'option_c', 'option_d', 'correct_option'
    ]));

    \Log::info('Quiz creato', $quiz->toArray());

    return response()->json([
        'message' => 'Domanda creata con successo!',
        'quiz' => $quiz
    ], 201);
}
}