<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class FrontendController extends Controller
{
    public function __construct()
    {
    }

    public function dashboard(Request $request)
    {
        $validated = $request->validate([
            'token' => 'required|string'
        ]);
        $user = User::with('treatments.patient.user')
            ->where('uuid', $validated['token'])
            ->first();

        return Inertia::render('Dashboard', [
            'user' => $user
        ]);
    }
}
