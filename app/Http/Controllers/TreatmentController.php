<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TreatmentController extends Controller
{
    public function index(Request $request)
    {
        return response()->json([
            'progress' => 70
        ]);
    }
}