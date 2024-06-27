<?php

namespace App\Http\Controllers;

use App\Http\Requests\StartTreatmentRequest;
use App\Http\Requests\TreatmentStoreRequest;
use App\Http\Resources\RawTreatmentResource;
use App\Http\Resources\TreatmentResource;
use App\Models\Notification;
use App\Models\Patient;
use App\Models\Treatment;
use App\Services\CreateNotificationsForTreatmentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class TreatmentController extends Controller
{
    public function __construct(private CreateNotificationsForTreatmentService $createNotificationsForTreatmentService)
    {
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
                'starts_at' => now(),
                'ends_at' => now()->addDays($validated['duration']),
                'minimum_percentage' => $validated['minimum_percentage'],
                'status' => Treatment::STATUS_PENDING,
                'responsible_uuid' => $validated['user_uuid'],
                'questions_per_day' => $validated['questions_per_day'],
                'patient_uuid' => $validated['patient_uuid'],
                'clinic_uuid' => Patient::with('user.clinic')->where('uuid', $validated['patient_uuid'])->first()->user->clinic->uuid
            ]);

            // create notifications
            $this->createNotificationsForTreatmentService->handle($treatment);

            DB::commit();
        } catch (Throwable $e) {
            report($e);

            DB::rollBack();

            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }

        return response()->json(new TreatmentResource($treatment));
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

            logger('Response', [
                'response' => $response
            ]);

            $notification->response = $response;
            $notification->response_at = $notification->sent_at;

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
        return response()->json(RawTreatmentResource::make($treatment->load([
            'notifications' => function ($query) {
                $query->whereNull('response');
            }
        ])));
    }
}
