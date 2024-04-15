<?php

namespace App\Http\Controllers;

use App\Http\Requests\StartTreatmentRequest;
use App\Http\Requests\TreatmentStoreRequest;
use App\Http\Resources\TreatmentResource;
use App\Models\Notification;
use App\Models\Treatment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class TreatmentController extends Controller
{
    public function index(Request $request)
    {
        return response()->json([
            'progress' => 70
        ]);
    }

    public function show(Treatment $treatment)
    {
        return response()->json(TreatmentResource::make($treatment));
    }

    public function store(TreatmentStoreRequest $request)
    {
        $validated = $request->validated();

        try {
            DB::beginTransaction();

            $treatment = Treatment::create([
                ...$validated,
                'responsible_uuid' => auth()->id
            ]);

            // create notifications
            // $questions = $treatment->patient->user->clinic->questions;

            // foreach ($questions as $question) {
            //     Notification::create([]);
            // }

            DB::commit();
        } catch (Throwable $e) {
            report($e);

            DB::rollBack();
        }
    }

    public function start(StartTreatmentRequest $request)
    {
        $treatmentUuid = $request->validated('treatment_uuid');
        logger('Starting treatment', [
            'treatment_uuid' => $treatmentUuid
        ]);
        $tratmentUuid = $treatmentUuid;

        $treatment = Treatment::where('uuid', $tratmentUuid)->first();

        $treatment->starts_at = now();
        $treatment->status = Treatment::STATUS_IN_PROGRESS;
        $treatment->save();

        $treatment->load(['patient', 'responsible']);

        return response()->json(TreatmentResource::make($treatment));
    }

    public function getNotifications(Treatment $treatment)
    {
        return response()->json([
            'questions' => $treatment->patient->user->clinic->questions
        ]);
    }
}
