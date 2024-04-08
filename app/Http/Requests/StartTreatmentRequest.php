<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StartTreatmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // Add your authorization logic here
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
            'treatment_uuid' => 'required|uuid|exists:treatments,uuid',
        ];
    }
}
