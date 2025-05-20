<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClassRoomRequest extends FormRequest
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
        return [
            'name' => ['required', 'string', 'max:255'],
            'school_id' => ['required', 'exists:schools,id'],
            'grade_level' => ['required', 'string', 'max:50'],
            'section' => ['required', 'string', 'max:50'],
            'academic_year' => ['required', 'string', 'max:20'],
            'capacity' => ['required', 'integer', 'min:1'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i', 'after:start_time'],
            'days_of_week' => ['required', 'array', 'min:1'],
            'days_of_week.*' => ['required', 'string', 'in:monday,tuesday,wednesday,thursday,friday,saturday,sunday'],
            'room_number' => ['required', 'string', 'max:50'],
            'building' => ['required', 'string', 'max:100'],
            'floor' => ['required', 'string', 'max:50'],
            'is_active' => ['required', 'boolean'],
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
            'school_id' => 'school',
            'grade_level' => 'grade level',
            'academic_year' => 'academic year',
            'start_time' => 'start time',
            'end_time' => 'end time',
            'days_of_week' => 'days of week',
            'room_number' => 'room number',
            'is_active' => 'active status',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'days_of_week.required' => 'At least one day must be selected.',
            'days_of_week.*.in' => 'Invalid day selected.',
            'end_time.after' => 'End time must be after start time.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        if ($this->has('days_of_week') && is_string($this->days_of_week)) {
            $this->merge([
                'days_of_week' => json_decode($this->days_of_week, true)
            ]);
        }

        if ($this->has('is_active')) {
            $this->merge([
                'is_active' => $this->is_active === 'true' || $this->is_active === '1' || $this->is_active === true
            ]);
        }
    }
}
