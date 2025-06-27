<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/
use App\Services\QuizService;


$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->post('/register', function (Illuminate\Http\Request $request) {
    $this->validate($request, [
        'username'    => 'required|unique:users',
        'first_name'  => 'required|string',
        'last_name'   => 'required|string',
        'email'       => 'required|email|unique:users',
        'password'    => 'required|min:6',
    ]);

    $user = \App\Models\User::create([
        'username'   => $request->input('username'),
        'first_name' => $request->input('first_name'),
        'last_name'  => $request->input('last_name'),
        'email'      => $request->input('email'),
        'password'   => app('hash')->make($request->input('password')),
    ]);

    return response()->json(['user' => $user], 201);
});

$router->post('/login', function (Illuminate\Http\Request $request) {
    $this->validate($request, [
        'email'    => 'required|email',
        'password' => 'required'
    ]);

    $user = \App\Models\User::where('email', $request->input('email'))->first();

    if (!$user || !app('hash')->check($request->input('password'), $user->password)) {
        return response()->json(['message' => 'Email o password non validi'], 401);
    }

    // Token fittizio per esempio (puoi sostituirlo con JWT, Passport, ecc.)
    $token = bin2hex(random_bytes(40));

    // Se vuoi salvare il token nel DB, aggiungi un campo `api_token` nella tabella users
    $user->api_token = $token;
    $user->save();

    return response()->json(['token' => $token, 'user' => $user], 200);
});

$router->get('/users', 'UserController@index');

$router->options('{any:.*}', function () {
    return response('', 200);
});

$router->get('/api/quizzes', 'QuizController@index');  // per leggere i quiz
$router->post('/api/quizzes', 'QuizController@store'); // per aggiungerli

$router->get('/leaderboard', 'ScoreController@leaderboard');

$router->post('/score/update/{id}', 'PunteggioController@aggiorna');

// In routes/web.php o api.php
$router->get('/api/leaderboard', 'PunteggioController@classifica');