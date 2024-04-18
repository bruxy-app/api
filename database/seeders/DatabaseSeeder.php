<?php

namespace Database\Seeders;

use App\Models\Clinic;
use App\Models\Patient;
use App\Models\Question;
use App\Models\Treatment;
use App\Models\User;
use App\Services\CreateNotificationsForTreatmentService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function __construct(private CreateNotificationsForTreatmentService $createNotificationsForTreatmentService)
    {
    }
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $clinic = Clinic::factory()->create([
            'name' => 'Test Clinic'
        ]);

        for ($i = 0; $i < 5; $i++) {
            Question::factory()->create([
                'question' => 'Question ' . $i,
                'options' => ['Option 1', 'Option 2', 'Option 3'],
                'clinic_uuid' => $clinic->uuid
            ]);
        }

        $professionalUser = User::factory()->create([
            'name' => 'Test User',
            'email' => 'professional@example.com',
            'type' => 'professional',
            'password' => Hash::make('password'),
            'clinic_uuid' => $clinic->uuid
        ]);

        $patientUser = User::factory()->create([
            'name' => 'Test Patient',
            'email' => 'patient@email.com',
            'type' => 'patient',
            'password' => Hash::make('password'),
            'clinic_uuid' => $clinic->uuid
        ]);

        $patient = Patient::factory()->create([
            'sex' => 'm',
            'access_code' => '123456',
            'user_uuid' => $patientUser->uuid
        ]);

        $treatment = Treatment::factory()->create([
            'starts_at' => now(),
            'ends_at' => now()->addDays(7),
            'minimum_percentage' => 70,
            'status' => Treatment::STATUS_PENDING,
            'questions_per_day' => 7,
            'clinic_uuid' => $clinic->uuid,
            'patient_uuid' => $patient->uuid,
            'responsible_uuid' => $professionalUser->uuid
        ]);

        $this->createNotificationsForTreatmentService->handle($treatment);
    }
}
