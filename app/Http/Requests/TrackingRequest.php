<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class TrackingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'tracking_code' => [
                'required',
                'string',
                'min:8',
                'max:20',
                'regex:/^[A-Z0-9]+$/',
            ]
        ];
    }

    public function messages(): array
    {
        return [
            'tracking_code.required' => 'Tracking code is required.',
            'tracking_code.min' => 'Tracking code must be at least 8 characters.',
            'tracking_code.max' => 'Tracking code cannot exceed 20 characters.',
            'tracking_code.regex' => 'Tracking code must contain only uppercase letters and numbers.'
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $validator->errors()
            ], 422)
        );
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'tracking_code' => strtoupper(trim($this->tracking_code ?? ''))
        ]);
    }
}
