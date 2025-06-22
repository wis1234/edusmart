<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SubjectRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Authorization is handled in the controller
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $subjectId = $this->route('subject')?->id;
        
        return [
            'name' => ['required', 'string', 'max:255'],
            'code' => [
                'nullable', 
                'string', 
                'max:50',
                Rule::unique('subjects', 'code')->ignore($subjectId)
            ],
            'description' => ['nullable', 'string', 'max:1000'],
            'credits' => ['nullable', 'integer', 'min:0', 'max:999'],
            'level' => ['nullable', 'string', 'max:50', 'in:primary,secondary,high,university'],
            'hours_per_week' => ['nullable', 'integer', 'min:0', 'max:168'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'name' => 'subject name',
            'code' => 'subject code',
            'description' => 'subject description',
            'credits' => 'credits',
            'level' => 'academic level',
            'hours_per_week' => 'hours per week',
            'is_active' => 'active status',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'The subject name is required.',
            'code.unique' => 'This subject code is already in use.',
            'level.in' => 'Please select a valid academic level.',
            'credits.min' => 'Credits must be a positive number.',
            'hours_per_week.min' => 'Hours per week must be a positive number.',
            'hours_per_week.max' => 'Hours per week cannot exceed 168 (24 hours × 7 days).',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Ensure is_active is a boolean
        $this->merge([
            'is_active' => $this->has('is_active'),
        ]);
    }
}
