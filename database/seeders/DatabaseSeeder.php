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

        // set fixed questions for trial period
        $this->createQuestions($clinic);

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

    private function createQuestions(Clinic $clinic): void
    {
        $questions = [
            [
                'question' => 'Qual é a sua condição atual?',
                'options' => ['Relaxado', 'Maxiliar contraído (sem os dentes em contato)', 'Contato dentário', 'Aperta os dentes', 'Range os dentes']
            ],
            [
                'question' => 'Você está com dor?',
                'options' => ['Sim', 'Não']
            ],
            [
                'question' => 'Como avalia sua dor de 0 a 10?',
                'options' => ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '10']
            ],
        ];

        foreach ($questions as $question) {
            Question::factory()->create([
                'question' => $question['question'],
                'options' => $question['options'],
                'clinic_uuid' => $clinic->uuid
            ]);
        }
    }
}
