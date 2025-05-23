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
    $rules = [];

    if ($this->isMethod('post')) {
        // Creation rules
        $rules = [
            'first_name' => 'required|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|max:20',
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:male,female,other',
            'address' => 'required|string|max:500',
            'password' => 'required|string|min:8|confirmed',
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
    } else {
        // Update rules: all fields optional, but validated if present
        $rules = [
           'first_name' => 'sometimes|string|max:255',
            'last_name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,' . $this->teacher->user_id,
            'phone' => 'sometimes|string|max:20',
            'date_of_birth' => 'sometimes|date',
            'gender' => 'sometimes|in:male,female,other',
            'address' => 'sometimes|string|max:500',
            'password' => 'nullable|string|min:8|confirmed',
            'profile_photo' => 'nullable|image|max:2048',
            'grade' => 'sometimes|string|max:255',
            'speciality' => 'sometimes|string|max:255',
            'subject_title' => 'sometimes|string|max:255',
            'status' => 'sometimes|in:active,inactive,on_leave',
            'schools' => 'sometimes|array|min:1',
            'schools.*' => 'required_with:schools|exists:schools,id',
            'subjects' => 'sometimes|array|min:1',
            'subjects.*' => 'required_with:subjects|exists:subjects,id',
            'class_rooms' => 'sometimes|array|min:1',
            'class_rooms.*' => 'required_with:class_rooms|exists:class_rooms,id',
            'years' => 'sometimes|array|min:1',
            'years.*' => 'required_with:years|integer|min:2000|max:2100',
        ];
    }

    return $rules;
}

}
