<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
        ]);

        $user = User::where('email', $credentials['email'])->where('type', 'professional')->first();
        if ($user) {
            return response()->json(['uuid' => $user->uuid]);
        }

        return response()->json([
            'error' => 'Usuário não encontrado'
        ], 404);
    }
}
