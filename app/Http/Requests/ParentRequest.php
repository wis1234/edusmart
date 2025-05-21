<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ParentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Authorization is handled in the controller/policy
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $parentId = $this->route('parent') ? $this->route('parent')->id : null;

        return [
            'user_id' => 'required|exists:users,id|unique:parents,user_id' . ($parentId ? ',' . $parentId : ''),
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
            'status' => 'nullable|in:active,inactive',
        ];
    }
}
