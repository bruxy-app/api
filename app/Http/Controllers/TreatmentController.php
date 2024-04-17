<?php

namespace App\Http\Controllers;

use App\Http\Requests\StartTreatmentRequest;
use App\Http\Requests\TreatmentStoreRequest;
use App\Http\Resources\TreatmentResource;
use App\Models\Notification;
use App\Models\Patient;
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
                'responsible_uuid' => auth()->id,
                'clinic_uuid' => Patient::where('uuid', $validated['patient_uuid'])->first()->uuid
            ]);

            // create notifications
            $durationInDays = $treatment->starts_at->diffInDays($treatment->ends_at);
            $questionsPerDay = $treatment->questions_per_day;

            for ($i = 0; $i < $durationInDays; $i++) {
                for ($j = 0; $j < $questionsPerDay; $j++) {
                    foreach ($treatment->clinic->questions as $question) {
                        $treatment->notifications()->create([
                            'question' => $question->question,
                            'options' => $question->options,
                            // TODO: update this later
                            'sent_at' => $treatment->starts_at->addDays($i)
                        ]);
                    }
                }
            }


            DB::commit();
        } catch (Throwable $e) {
            report($e);

            DB::rollBack();
        }
    }

    public function storeResponse(Notification $notification, Request $request)
    {
        DB::beginTransaction();
        try {
            logger('Storing response', [
                'request' => $request->all()
            ]);
            $questions = $request->input('notification')['questions'];
            $response = [];
            foreach ($questions as $key => $question) {
                $response[$key] = [
                    $question['response']
                ];
            }

            $notification->response = $response;
            $notification->response_at = now();

            $notification->save();

            DB::commit();
        } catch (Throwable $e) {
            report($e);

            DB::rollBack();

            return response()->json([
                'message' => 'An error occurred'
            ], 500);
        }

        return response()->json(TreatmentResource::make($notification->treatment));
    }

    public function start(StartTreatmentRequest $request)
    {
        try {
            $treatmentUuid = $request->validated('treatment_uuid');
            logger('Starting treatment', [
                'treatment_uuid' => $treatmentUuid
            ]);
            $tratmentUuid = $treatmentUuid;

            $treatment = Treatment::where('uuid', $tratmentUuid)->first();

            if ($treatment->status === Treatment::STATUS_IN_PROGRESS) {
                $treatment->load(['patient', 'responsible']);

                return response()->json(TreatmentResource::make($treatment));
            }

            if ($treatment->status === Treatment::STATUS_COMPLETED) {
                return response()->json([
                    'message' => 'Treatment already completed'
                ], 400);
            }

            $treatment->starts_at = now();
            $treatment->status = Treatment::STATUS_IN_PROGRESS;
            $treatment->save();

            $treatment->load(['patient', 'responsible']);
        } catch (Throwable $e) {
            report($e);

            return response()->json([
                'message' => 'An error occurred'
            ], 500);
        }

        return response()->json(TreatmentResource::make($treatment));
    }

    public function getNotifications(Treatment $treatment)
    {
        return response()->json(TreatmentResource::make($treatment->load('notifications')));
    }
}
