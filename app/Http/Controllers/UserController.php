<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        echo "Elenco degli utenti registrati:\n";
        return response()->json($users);
    }


    public function register(Request $request)
    {
        $this->validate($request, [
            'username' => 'required|unique:users,username|min:3'
        ]);

        $user = User::create([
            'username' => $request->input('username')

        ]);

        return response()->json([
            'message' => 'Registrazione avvenuta con successo',
            'user' => $user
        ]);
    }

   public function login(Request $request)
{
    $this->validate($request, [
        'username' => 'required|string'
    ]);

    $user = User::where('username', $request->input('username'))->first();

    if (!$user) {
        return response()->json([
            'message' => 'Utente non trovato'
        ], 404);
    }

    // Puoi aggiungere qui il controllo della password, se necessario

    return response()->json([
        'message' => 'Login riuscito',
        'user' => [
            'id'         => $user->id,
            'username'   => $user->username,
            'first_name' => $user->first_name,
            'last_name'  => $user->last_name,
            'email'      => $user->email
        ]
    ], 200);
}
}