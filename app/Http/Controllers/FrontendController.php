<?php

namespace App\Http\Controllers;

use App\Http\Resources\PatientResource;
use App\Models\Patient;
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
        $user = User::with(['treatments.patient.user', 'clinic.patients.patient.treatment'])
            ->where('uuid', $validated['token'])
            ->first();

        return Inertia::render('Dashboard', [
            'user' => $user
        ]);
    }

    public function newPatient()
    {
        return Inertia::render('NewPatient');
    }
    public function patientDetails(Patient $patient)
    {
        return Inertia::render('PatientDetails', [
            'patient' => PatientResource::make($patient->load(['user', 'treatment.notifications'])),
        ]);
    }
}
