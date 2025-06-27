<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class PunteggioController extends Controller
{
   public function aggiorna($id, Request $request)
{
    \Log::info("Update richiesto per user {$id}: score = " . $request->score);

    $user = User::find($id);
    if (!$user) {
        return response()->json(['message' => 'Utente non trovato'], 404);
    }

    $this->validate($request, [
        'score' => 'required|integer|min:0',
    ]);

    if ($request->score > $user->punteggio) {
        $user->punteggio = $request->score;
        $user->save();
    }

    return response()->json([
        'message' => 'Punteggio aggiornato',
        'score' => $user->punteggio
    ]);
}
 public function classifica()
{
    $users = User::orderBy('punteggio', 'desc')
        ->select('id', 'username', 'punteggio')
        ->get();

    return response()->json($users);
}
}