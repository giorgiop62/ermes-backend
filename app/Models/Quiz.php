<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    protected $table = 'quizzes';

    protected $fillable = [
        'question', 'option_a', 'option_b', 'option_c', 'option_d', 'correct_option'
    ];

    public $timestamps = false;
}