<?php

namespace App\Http\Controllers;

use App\Http\Requests\StartTreatmentRequest;
use App\Models\Treatment;
use Illuminate\Http\Request;

class TreatmentController extends Controller
{
    public function index(Request $request)
    {
        return response()->json([
            'progress' => 70
        ]);
    }

    public function start(StartTreatmentRequest $request)
    {
        $tratmentUuid = $request->validated('treatment_uuid');

        Treatment::where('uuid', $tratmentUuid)->update([
            'starts_at' => now(),
            'status' => Treatment::STATUS_IN_PROGRESS
        ]);

        return response()->json([
            'message' => 'Treatment started'
        ]);
    }
}
