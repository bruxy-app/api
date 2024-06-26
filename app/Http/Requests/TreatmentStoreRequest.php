<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TreatmentStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            // 'starts_at' => 'required|date',
            // 'ends_at' => 'required|date',
            'duration' => 'required|integer',
            'questions_per_day' => 'nullable|integer',
            'user_uuid' => 'required|exists:users,uuid',
            'patient_uuid' => 'required|exists:patients,uuid',
        ];
    }
}
