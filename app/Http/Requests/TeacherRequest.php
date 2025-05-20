<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TeacherRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Authorization is handled by policies
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $teacherId = $this->route('teacher')?->id;

        return [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['nullable', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('teachers', 'teacher_email')->ignore($teacherId)
            ],
            'password' => $this->isMethod('post') ? ['required', 'string', 'min:8', 'confirmed'] : ['nullable', 'string', 'min:8', 'confirmed'],
            'phone' => ['required', 'string', 'max:20'],
            'date_of_birth' => ['required', 'date'],
            'gender' => ['required', 'string', Rule::in(['male', 'female', 'other'])],
            'address' => ['required', 'string'],
            'grade' => ['required', 'string'],
            'speciality' => ['required', 'string'],
            'subject_title' => ['required', 'string'],
            'status' => ['required', Rule::in(['active', 'inactive', 'on_leave'])],
            'profile_photo' => ['nullable', 'image', 'max:2048'],
            'school_id' => ['nullable', 'exists:schools,id'],

            // Teaching assignments
            'subjects' => ['required', 'array', 'min:1'],
            'subjects.*' => ['required', 'exists:subjects,id'],
            'class_rooms' => ['required', 'array', 'min:1'],
            'class_rooms.*' => ['required', 'exists:class_rooms,id'],
            'years' => ['required', 'array', 'min:1'],
            'years.*' => ['required', 'integer', 'min:2000', 'max:2100'],
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
            'subjects.*' => 'subject',
            'class_rooms.*' => 'class room',
            'years.*' => 'year',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Split name into first and last name for storage
        if ($this->has('name')) {
            $nameParts = explode(' ', $this->name, 2);
            $this->merge([
                'teacher_firstname' => $nameParts[0],
                'teacher_lastname' => $nameParts[1] ?? null,
            ]);
        }

        // Convert email to teacher_email for storage
        if ($this->has('email')) {
            $this->merge([
                'teacher_email' => $this->email,
            ]);
        }

        // Convert phone to teacher_phone for storage
        if ($this->has('phone')) {
            $this->merge([
                'teacher_phone' => $this->phone,
            ]);
        }
    }
}
