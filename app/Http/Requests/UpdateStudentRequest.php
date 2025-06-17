namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateStudentRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:students,email,' . $this->student->id],
            'date_of_birth' => ['required', 'date'],
            'gender' => ['required', 'string', 'in:male,female,other'],
            'blood_group' => ['required', 'string', 'in:A+,A-,B+,B-,AB+,AB-,O+,O-'],
            'address' => ['required', 'string'],
            'medical_conditions' => ['nullable', 'array'],
            'medical_conditions.*' => ['string', 'in:Asthma,Diabetes,Epilepsy,Heart Condition,Allergies,Other'],
            'emergency_contact' => ['required', 'string', 'max:255'],
            'class_room_id' => ['required', 'exists:class_rooms,id'],
            'school_id' => ['required', 'exists:schools,id'],
            'academic_year' => ['required', 'string', 'digits:4'],
            'status' => ['required', 'string', 'in:active,inactive'],
            'parent_id' => ['nullable', 'exists:parents,id'],
            'profile_photo' => ['nullable', 'image', 'max:2048'], // 2MB max
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Please enter the student\'s name.',
            'email.required' => 'Please enter the student\'s email address.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email address is already in use.',
            'date_of_birth.required' => 'Please enter the student\'s date of birth.',
            'date_of_birth.date' => 'Please enter a valid date.',
            'gender.required' => 'Please select the student\'s gender.',
            'gender.in' => 'Please select a valid gender option.',
            'blood_group.required' => 'Please select the student\'s blood group.',
            'blood_group.in' => 'Please select a valid blood group.',
            'address.required' => 'Please enter the student\'s address.',
            'medical_conditions.array' => 'Medical conditions must be a list.',
            'medical_conditions.*.in' => 'Please select valid medical conditions.',
            'emergency_contact.required' => 'Please enter an emergency contact number.',
            'class_room_id.required' => 'Please select a class room.',
            'class_room_id.exists' => 'The selected class room does not exist.',
            'school_id.required' => 'Please select a school.',
            'school_id.exists' => 'The selected school does not exist.',
            'academic_year.required' => 'Please select an academic year.',
            'academic_year.digits' => 'Academic year must be a 4-digit year.',
            'status.required' => 'Please select a status.',
            'status.in' => 'Please select a valid status.',
            'parent_id.exists' => 'The selected parent does not exist.',
            'profile_photo.image' => 'The profile photo must be an image.',
            'profile_photo.max' => 'The profile photo must not be larger than 2MB.',
        ];
    }
} 