<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TeacherRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Adjust authorization logic as needed
    }

    public function rules()
    {
        return [
            'first_name' => 'required|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'email' => 'required|email|unique:users,email,' . $this->teacher?->user_id,
            'phone' => 'required|string|max:20',
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:male,female,other',
            'address' => 'required|string|max:500',
            'password' => $this->isMethod('post') ? 'required|string|min:8|confirmed' : 'nullable|string|min:8|confirmed',
            'profile_photo' => 'nullable|image|max:2048',
            'grade' => 'required|string|max:255',
            'speciality' => 'required|string|max:255',
            'subject_title' => 'required|string|max:255',
            'status' => 'required|in:active,inactive,on_leave',
            'schools' => 'required|array|min:1',
            'schools.*' => 'required|exists:schools,id',
            'subjects' => 'required|array|min:1',
            'subjects.*' => 'required|exists:subjects,id',
            'class_rooms' => 'required|array|min:1',
            'class_rooms.*' => 'required|exists:class_rooms,id',
            'years' => 'required|array|min:1',
            'years.*' => 'required|integer|min:2000|max:2100',
        ];
    }
}
