<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Clinic;
use App\Models\Notification;
use App\Models\Patient;
use App\Models\Question;
use App\Models\Treatment;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
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

        $duration = $treatment->starts_at->diffInDays($treatment->ends_at);
        for ($i = 0; $i < $duration; $i++) {
            for ($j = 0; $j < $treatment->questions_per_day; $j++) {
                foreach ($clinic->questions as $question) {
                    Notification::create([
                        'treatment_uuid' => $treatment->uuid,
                        'question_uuid' => $question->uuid,
                        'question' => $question->question,
                        'options' => $question->options,
                        // between 8:00 and 18:00
                        'sent_at' => $treatment->starts_at->addDays($i)->setHour(rand(8, 18))->setMinute(rand(0, 59))
                    ]);
                }
            }
        }
    }
}
