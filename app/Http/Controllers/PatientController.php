<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\User;
use Exception;
use Faker\Factory as Faker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PatientController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'professional_uuid' => 'required|string|exists:users,uuid',
        ]);
        $professional = User::where('uuid', $validated['professional_uuid'])->first();

        DB::beginTransaction();
        try {
            $faker = Faker::create();
            $patientUser = User::create([
                'name' => $validated['name'],
                'email' => $faker->unique()->safeEmail,
                'type' => 'patient',
                'password' => 'teste123',
                'clinic_uuid' => $professional->clinic_uuid
            ]);
            Patient::create([
                'user_uuid' => $patientUser->uuid,
                'access_code' => '123456'
            ]);

            DB::commit();
        } catch (Exception $e) {
            report($e);

            DB::rollBack();

            return response()->json([
                'error' => $e->getMessage()
            ], 400);
        }

        return response()->redirectTo('/dashboard');
    }
}
