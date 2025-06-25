<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - EduSmart</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .step-active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            transform: scale(1.1);
        }
        .step-completed {
            background: linear-gradient(135deg, #4ade80 0%, #22c55e 100%);
            color: white;
        }
        .role-card {
            transition: all 0.3s ease;
            cursor: pointer;
        }
        .role-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        .role-card.selected {
            border-color: #667eea;
            background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
            transform: translateY(-5px);
        }
        .form-step {
            transition: opacity 0.5s ease-in-out;
        }
        .form-step:not(.active) {
            display: none;
        }
        .form-step.active {
            display: block;
        }
        .client-error {
            display: none;
            color: #e53e3e; /* text-red-600 */
            font-size: 0.875rem; /* text-sm */
            margin-top: 0.25rem; /* mt-1 */
            align-items: center;
        }
        .client-error svg {
            width: 1rem;
            height: 1rem;
            margin-right: 0.25rem;
        }
        .input-error {
            border-color: #e53e3e !important;
        }
        .input-error:focus {
            --tw-ring-color: #e53e3e !important;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateX(20px); }
            to { opacity: 1; transform: translateX(0); }
        }
    </style>
</head>
<body class="bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 min-h-screen">
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl w-full space-y-8">
            <div class="text-center">
                <h2 class="text-4xl font-bold text-gray-900 mb-2">Create Your Account</h2>
                <p class="text-gray-600">Join our educational platform in just a few steps</p>
            </div>
            <div class="bg-white rounded-lg shadow-lg p-6">
                <div class="flex items-center justify-between mb-8">
                    <div class="flex items-center space-x-4">
                        <div class="step-indicator w-10 h-10 rounded-full flex items-center justify-center text-sm font-semibold step-active" data-step="1">1</div>
                        <div class="step-indicator w-10 h-10 rounded-full flex items-center justify-center text-sm font-semibold bg-gray-200 text-gray-600" data-step="2">2</div>
                        <div class="step-indicator w-10 h-10 rounded-full flex items-center justify-center text-sm font-semibold bg-gray-200 text-gray-600" data-step="3">3</div>
                        <div class="step-indicator w-10 h-10 rounded-full flex items-center justify-center text-sm font-semibold bg-gray-200 text-gray-600" data-step="4">4</div>
                        <div class="step-indicator w-10 h-10 rounded-full flex items-center justify-center text-sm font-semibold bg-gray-200 text-gray-600" data-step="5">5</div>
                        <div class="step-indicator w-10 h-10 rounded-full flex items-center justify-center text-sm font-semibold bg-gray-200 text-gray-600" data-step="6">6</div>
                    </div>
                    <div class="text-sm text-gray-500">
                        Step <span id="current-step">1</span> of 6
        </div>
                </div>
                @if ($errors->any())
                    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6">
                        <div class="font-medium">Please fix the following errors:</div>
                        <ul class="list-disc list-inside mt-2 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form id="register-form" method="POST" action="{{ route('register') }}" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    <input type="hidden" id="selected-role" name="role" value="{{ old('role') }}">
                    <!-- Step 1: Role Selection -->
                    <div class="form-step active" id="step-1">
                        <h3 class="text-2xl font-semibold text-gray-900 mb-6">Register as</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="role-card border-2 border-gray-200 rounded-xl p-6 text-center" data-role="student">
                                <div class="w-16 h-16 mx-auto mb-4 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center">
                                    <i class="fas fa-user-graduate text-white text-2xl"></i>
                                </div>
                                <h4 class="text-lg font-semibold text-gray-900 mb-2">Student</h4>
                                <p class="text-gray-600 text-sm">Join as a student to access your courses and grades</p>
                            </div>
                            <div class="role-card border-2 border-gray-200 rounded-xl p-6 text-center" data-role="teacher">
                                <div class="w-16 h-16 mx-auto mb-4 bg-gradient-to-br from-green-500 to-green-600 rounded-full flex items-center justify-center">
                                    <i class="fas fa-chalkboard-teacher text-white text-2xl"></i>
                                </div>
                                <h4 class="text-lg font-semibold text-gray-900 mb-2">Teacher</h4>
                                <p class="text-gray-600 text-sm">Join as a teacher to manage your classes and students</p>
                            </div>
                            <div class="role-card border-2 border-gray-200 rounded-xl p-6 text-center" data-role="parent">
                                <div class="w-16 h-16 mx-auto mb-4 bg-gradient-to-br from-purple-500 to-purple-600 rounded-full flex items-center justify-center">
                                    <i class="fas fa-users text-white text-2xl"></i>
                                </div>
                                <h4 class="text-lg font-semibold text-gray-900 mb-2">Parent</h4>
                                <p class="text-gray-600 text-sm">Join as a parent to monitor your child's progress</p>
                            </div>
                        </div>
                    </div>
                    <!-- Step 2: Basic Information -->
                    <div class="form-step" id="step-2">
                        <h3 class="text-2xl font-semibold text-gray-900 mb-6">Basic Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="first_name" class="block text-sm font-medium text-gray-700 mb-2">First Name <span class="text-red-500">*</span></label>
                                <input id="first_name" name="first_name" type="text" value="{{ old('first_name') }}" data-must-required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>
                            <div>
                                <label for="last_name" class="block text-sm font-medium text-gray-700 mb-2">Last Name <span class="text-red-500">*</span></label>
                                <input id="last_name" name="last_name" type="text" value="{{ old('last_name') }}" data-must-required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address <span class="text-red-500">*</span></label>
                                <input id="email" name="email" type="email" value="{{ old('email') }}" data-must-required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>
                            <div>
                                <label for="school_id" class="block text-sm font-medium text-gray-700 mb-2">School <span class="text-red-500">*</span></label>
                                <select id="school_id" name="school_id" data-must-required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="">Select a school</option>
                                    @foreach($schools as $school)
                                        <option value="{{ $school->id }}" @if(old('school_id')==$school->id) selected @endif>{{ $school->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <!-- Step 3: Role-Specific Information -->
                    <div class="form-step" id="step-3">
                        <h3 class="text-2xl font-semibold text-gray-900 mb-6">Additional Information</h3>
                        <!-- Student Fields -->
                        <div id="student-fields" style="display: none;">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="date_of_birth" class="block text-sm font-medium text-gray-700 mb-2">Date of Birth <span class="text-red-500">*</span></label>
                                    <input id="date_of_birth" name="date_of_birth" type="date" value="{{ old('date_of_birth') }}" data-must-required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                </div>
                                <div>
                                    <label for="gender" class="block text-sm font-medium text-gray-700 mb-2">Gender <span class="text-red-500">*</span></label>
                                    <select id="gender" name="gender" data-must-required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                        <option value="">Select Gender</option>
                                        <option value="male" @if(old('gender')=='male') selected @endif>Male</option>
                                        <option value="female" @if(old('gender')=='female') selected @endif>Female</option>
                                        <option value="other" @if(old('gender')=='other') selected @endif>Other</option>
                                    </select>
                                </div>
                                <div>
                                    <label for="admission_date" class="block text-sm font-medium text-gray-700 mb-2">Admission Date <span class="text-red-500">*</span></label>
                                    <input id="admission_date" name="admission_date" type="date" value="{{ old('admission_date') }}" data-must-required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                </div>
                                <div>
                                    <label for="academic_year" class="block text-sm font-medium text-gray-700 mb-2">Academic Year <span class="text-red-500">*</span></label>
                                    <input id="academic_year" name="academic_year" type="text" value="{{ old('academic_year') }}" placeholder="e.g. 2023-2024" data-must-required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                </div>
                            </div>
                        </div>
                        <!-- Teacher Fields -->
                        <div id="teacher-fields" style="display: none;">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="speciality" class="block text-sm font-medium text-gray-700 mb-2">Speciality <span class="text-red-500">*</span></label>
                                    <input id="speciality" name="speciality" type="text" value="{{ old('speciality') }}" data-must-required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                </div>
                                <div>
                                    <label for="teacher_phone" class="block text-sm font-medium text-gray-700 mb-2">Phone <span class="text-red-500">*</span></label>
                                    <input id="teacher_phone" name="teacher_phone" type="text" value="{{ old('teacher_phone') }}" data-must-required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                </div>
                                <div>
                                    <label for="date_of_birth_teacher" class="block text-sm font-medium text-gray-700 mb-2">Date of Birth <span class="text-red-500">*</span></label>
                                    <input id="date_of_birth_teacher" name="date_of_birth" type="date" value="{{ old('date_of_birth') }}" data-must-required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                </div>
                                <div>
                                    <label for="gender_teacher" class="block text-sm font-medium text-gray-700 mb-2">Gender <span class="text-red-500">*</span></label>
                                    <select id="gender_teacher" name="gender" data-must-required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                        <option value="">Select Gender</option>
                                        <option value="male" @if(old('gender')=='male') selected @endif>Male</option>
                                        <option value="female" @if(old('gender')=='female') selected @endif>Female</option>
                                        <option value="other" @if(old('gender')=='other') selected @endif>Other</option>
                                    </select>
                                </div>
                                <div>
                                    <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Address <span class="text-red-500">*</span></label>
                                    <input id="address" name="address" type="text" value="{{ old('address') }}" data-must-required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                </div>
                                <div>
                                    <label for="grade" class="block text-sm font-medium text-gray-700 mb-2">Grade <span class="text-red-500">*</span></label>
                                    <input id="grade" name="grade" type="text" value="{{ old('grade') }}" data-must-required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                </div>
                            </div>
                        </div>
                        <!-- Parent Fields -->
                        <div id="parent-fields" style="display: none;">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="profession" class="block text-sm font-medium text-gray-700 mb-2">Profession</label>
                                    <input id="profession" name="profession" type="text" value="{{ old('profession') }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                </div>
                                <div>
                                    <label for="phone_parent" class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                                    <input id="phone_parent" name="phone" type="tel" value="{{ old('phone') }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Step 4: Optional Information -->
                    <div class="form-step" id="step-4">
                        <h3 class="text-2xl font-semibold text-gray-900 mb-6">Optional Information</h3>
                        <!-- Student Optional Fields -->
                        <div id="student-optional" style="display: none;">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="blood_group" class="block text-sm font-medium text-gray-700 mb-2">Blood Group</label>
                                    <input id="blood_group" name="blood_group" type="text" value="{{ old('blood_group') }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
            <div>
                                    <label for="emergency_contact" class="block text-sm font-medium text-gray-700 mb-2">Emergency Contact</label>
                                    <input id="emergency_contact" name="emergency_contact" type="text" value="{{ old('emergency_contact') }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                </div>
                            </div>
                            <div class="mt-6">
                                <label for="address_student" class="block text-sm font-medium text-gray-700 mb-2">Address</label>
                                <textarea id="address_student" name="address" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('address') }}</textarea>
                            </div>
                            <div class="mt-6">
                                <label for="medical_conditions" class="block text-sm font-medium text-gray-700 mb-2">Medical Conditions</label>
                                <textarea id="medical_conditions" name="medical_conditions" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('medical_conditions') }}</textarea>
                            </div>
                        </div>
                        <!-- Teacher Optional Fields -->
                        <div id="teacher-optional" style="display: none;">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="profile_photo_teacher" class="block text-sm font-medium text-gray-700 mb-2">Profile Photo</label>
                                    <input id="profile_photo_teacher" name="profile_photo" type="file" accept="image/*" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                </div>
                            </div>
                </div>
                        <!-- Parent Optional Fields -->
                        <div id="parent-optional" style="display: none;">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="date_of_birth_parent" class="block text-sm font-medium text-gray-700 mb-2">Date of Birth</label>
                                    <input id="date_of_birth_parent" name="date_of_birth" type="date" value="{{ old('date_of_birth') }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
            <div>
                                    <label for="profile_photo_parent" class="block text-sm font-medium text-gray-700 mb-2">Profile Photo</label>
                                    <input id="profile_photo_parent" name="profile_photo" type="file" accept="image/*" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                </div>
                            </div>
                            <div class="mt-6">
                                <label for="address_parent" class="block text-sm font-medium text-gray-700 mb-2">Address</label>
                                <textarea id="address_parent" name="address" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('address') }}</textarea>
                            </div>
                        </div>
                </div>
                    <!-- Step 5: Security -->
                    <div class="form-step" id="step-5">
                        <h3 class="text-2xl font-semibold text-gray-900 mb-6">Create Your Password</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password <span class="text-red-500">*</span></label>
                                <input id="password" name="password" type="password" data-must-required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
            <div>
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Confirm Password <span class="text-red-500">*</span></label>
                                <input id="password_confirmation" name="password_confirmation" type="password" data-must-required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>
                        </div>
                    </div>
                    <!-- Step 6: Preview -->
                    <div class="form-step" id="step-6">
                        <h3 class="text-2xl font-semibold text-gray-900 mb-6">Preview Your Information</h3>
                        <div class="bg-gray-50 rounded-lg p-6">
                            <div id="preview-content">
                                <!-- Preview content will be dynamically generated -->
                            </div>
                        </div>
                    </div>
                    <!-- Navigation Buttons -->
                    <div class="flex justify-between mt-8">
                        <button type="button" id="prev-btn" class="px-6 py-3 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition duration-200" style="display: none;">
                            <i class="fas fa-arrow-left mr-2"></i> Previous
                        </button>
                        <button type="button" id="next-btn" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-200 ml-auto">
                            Next <i class="fas fa-arrow-right ml-2"></i>
                        </button>
                        <button type="button" id="preview-btn" class="px-6 py-3 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition duration-200" style="display: none;">
                            <i class="fas fa-eye mr-2"></i> Preview
                        </button>
                        <button type="submit" id="submit-btn" class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition duration-200" style="display: none;">
                            <i class="fas fa-check mr-2"></i> Create Account
                        </button>
                    </div>
                </form>
                </div>
            <div class="text-center">
                <p class="text-gray-600">Already have an account? 
                    <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-800 font-medium">Sign in here</a>
                </p>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let currentStep = 1;
            const totalSteps = 6;
            let selectedRole = document.getElementById('selected-role').value || '';

            const form = document.getElementById('register-form');
            const roleCards = document.querySelectorAll('.role-card');
            const stepIndicators = document.querySelectorAll('.step-indicator');
            const formSteps = document.querySelectorAll('.form-step');

            const prevBtn = document.getElementById('prev-btn');
            const nextBtn = document.getElementById('next-btn');
            const previewBtn = document.getElementById('preview-btn');
            const submitBtn = document.getElementById('submit-btn');
            
            // --- ROLE SELECTION ---
            roleCards.forEach(card => {
                card.addEventListener('click', function() {
                    roleCards.forEach(c => c.classList.remove('selected'));
                    this.classList.add('selected');
                    selectedRole = this.dataset.role;
                    document.getElementById('selected-role').value = selectedRole;
                });
            });

            // --- NAVIGATION ---
            function updateButtons() {
                prevBtn.style.display = currentStep > 1 ? 'inline-flex' : 'none';
                nextBtn.style.display = currentStep < 5 ? 'inline-flex' : 'none';
                previewBtn.style.display = currentStep === 5 ? 'inline-flex' : 'none';
                submitBtn.style.display = currentStep === totalSteps ? 'inline-flex' : 'none';
                
                // Ensure preview button leads to preview step
                if(currentStep === 5) {
                    previewBtn.onclick = () => {
                        if (validateStep(5)) {
                            currentStep++;
                            updateView();
                        }
                    };
                } else {
                     nextBtn.onclick = () => {
                        if (validateStep(currentStep)) {
                           currentStep++;
                           updateView();
                        }
                    };
                }
            }

            prevBtn.addEventListener('click', () => {
                currentStep--;
                updateView();
            });

            // --- VIEW UPDATE ---
            function updateView() {
                // Update step indicators
                stepIndicators.forEach((indicator, index) => {
                    indicator.classList.remove('step-active', 'step-completed');
                    if (index + 1 < currentStep) {
                        indicator.classList.add('step-completed');
                    } else if (index + 1 === currentStep) {
                        indicator.classList.add('step-active');
                    }
                });

                // Show current step form
                formSteps.forEach((step, index) => {
                    step.classList.toggle('active', (index + 1) === currentStep);
                });
                
                // Show role-specific fields within steps
                updateRoleSpecificFields();
                
                // Generate preview if on the last step
                if (currentStep === totalSteps) {
                    generatePreview();
                }

                document.getElementById('current-step').textContent = currentStep;
                updateButtons();
                updateRequiredAttributes();
            }
            
            function updateRoleSpecificFields() {
                const allRoleFields = ['student-fields', 'teacher-fields', 'parent-fields', 'student-optional', 'teacher-optional', 'parent-optional'];
                allRoleFields.forEach(id => {
                    const el = document.getElementById(id);
                    if (el) el.style.display = 'none';
                });
                
                if (currentStep === 3) {
                    if (selectedRole) {
                        const step3Fields = document.getElementById(`${selectedRole}-fields`);
                        if(step3Fields) step3Fields.style.display = 'block';
                    }
                } else if (currentStep === 4) {
                     if (selectedRole) {
                        const step4Fields = document.getElementById(`${selectedRole}-optional`);
                        if(step4Fields) step4Fields.style.display = 'block';
                    }
                }
            }

            // --- VALIDATION ---
            function validateStep(step) {
                clearAllErrors();
                let isValid = true;
                let fieldsToValidate = [];

                if (step === 1) {
                    if (!selectedRole) {
                        isValid = false;
                        alert('Please select a role to continue.');
                    }
                } else if (step === 2) {
                    fieldsToValidate = ['first_name', 'last_name', 'email', 'school_id'];
                } else if (step === 3 && selectedRole === 'student') {
                    fieldsToValidate = ['date_of_birth', 'gender', 'admission_date', 'academic_year'];
                } else if (step === 3 && selectedRole === 'teacher') {
                    fieldsToValidate = ['speciality', 'teacher_phone', 'date_of_birth_teacher', 'gender_teacher', 'address', 'grade'];
                } else if (step === 5) {
                    fieldsToValidate = ['password', 'password_confirmation'];
                }

                fieldsToValidate.forEach(name => {
                    const input = form.querySelector(`[name="${name}"]`);
                    if (input && !input.value) {
                        isValid = false;
                        showError(input, `${input.labels[0].innerText.replace('*','').trim()} is required.`);
                    }
                });
                
                if (step === 5 && form.querySelector('[name="password"]').value !== form.querySelector('[name="password_confirmation"]').value) {
                    isValid = false;
                    showError(form.querySelector('[name="password_confirmation"]'), 'Passwords do not match.');
                }
                
                return isValid;
            }

            function showError(input, message) {
                input.classList.add('input-error');
                const errorDiv = input.closest('div').querySelector('.client-error');
                if (errorDiv) {
                    errorDiv.innerHTML = `<svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12A9 9 0 11 3 12a9 9 0 0118 0z"/></svg>${message}`;
                    errorDiv.style.display = 'flex';
                }
            }
            
            function clearAllErrors() {
                form.querySelectorAll('.input-error').forEach(input => input.classList.remove('input-error'));
                form.querySelectorAll('.client-error').forEach(div => div.style.display = 'none');
            }
            
            // --- FORM SUBMISSION ---
            form.addEventListener('submit', function(e) {
                // Temporarily remove 'required' from all elements to bypass browser validation on hidden fields
                const form = document.getElementById('register-form');
                form.querySelectorAll('[required]').forEach(el => {
                    el.removeAttribute('required');
                });
                // Server-side validation will handle the rest.
            });

            function validateForm() {
                // Client-side validation is now handled per step, so this function is less critical on final submit.
                // We can keep the detailed error display logic here if we want to run it one last time.
                return true; 
            }
            
            // --- PREVIEW ---
            function generatePreview() {
                const previewContent = document.getElementById('preview-content');
                const formData = new FormData(document.getElementById('register-form'));
                let html = '<div class="space-y-4">';
                
                // Basic Information
                html += '<div class="border-b pb-4">';
                html += '<h4 class="text-lg font-semibold text-gray-900 mb-3">Basic Information</h4>';
                html += '<div class="grid grid-cols-1 md:grid-cols-2 gap-4">';
                html += `<div><span class="font-medium">Role:</span> <span class="capitalize">${formData.get('role') || 'Not selected'}</span></div>`;
                html += `<div><span class="font-medium">First Name:</span> ${formData.get('first_name') || 'Not provided'}</div>`;
                html += `<div><span class="font-medium">Last Name:</span> ${formData.get('last_name') || 'Not provided'}</div>`;
                html += `<div><span class="font-medium">Email:</span> ${formData.get('email') || 'Not provided'}</div>`;
                html += `<div><span class="font-medium">School:</span> ${document.getElementById('school_id').options[document.getElementById('school_id').selectedIndex]?.text || 'Not selected'}</div>`;
                html += '</div></div>';
                
                // Role-specific information
                const role = formData.get('role');
                if (role === 'student') {
                    html += '<div class="border-b pb-4">';
                    html += '<h4 class="text-lg font-semibold text-gray-900 mb-3">Student Information</h4>';
                    html += '<div class="grid grid-cols-1 md:grid-cols-2 gap-4">';
                    html += `<div><span class="font-medium">Date of Birth:</span> ${formData.get('date_of_birth') || 'Not provided'}</div>`;
                    html += `<div><span class="font-medium">Gender:</span> ${formData.get('gender') || 'Not selected'}</div>`;
                    html += `<div><span class="font-medium">Admission Date:</span> ${formData.get('admission_date') || 'Not provided'}</div>`;
                    html += `<div><span class="font-medium">Academic Year:</span> ${formData.get('academic_year') || 'Not provided'}</div>`;
                    html += '</div></div>';
                    
                    // Optional student fields
                    const bloodGroup = formData.get('blood_group');
                    const emergencyContact = formData.get('emergency_contact');
                    const address = formData.get('address');
                    const medicalConditions = formData.get('medical_conditions');
                    if (bloodGroup || emergencyContact || address || medicalConditions) {
                        html += '<div class="border-b pb-4">';
                        html += '<h4 class="text-lg font-semibold text-gray-900 mb-3">Additional Information</h4>';
                        html += '<div class="grid grid-cols-1 md:grid-cols-2 gap-4">';
                        if (bloodGroup) html += `<div><span class="font-medium">Blood Group:</span> ${bloodGroup}</div>`;
                        if (emergencyContact) html += `<div><span class="font-medium">Emergency Contact:</span> ${emergencyContact}</div>`;
                        html += '</div>';
                        if (address) html += `<div class="mt-2"><span class="font-medium">Address:</span> ${address}</div>`;
                        if (medicalConditions) html += `<div class="mt-2"><span class="font-medium">Medical Conditions:</span> ${medicalConditions}</div>`;
                        html += '</div>';
                    }
                } else if (role === 'teacher') {
                    html += '<div class="border-b pb-4">';
                    html += '<h4 class="text-lg font-semibold text-gray-900 mb-3">Teacher Information</h4>';
                    html += '<div class="grid grid-cols-1 md:grid-cols-2 gap-4">';
                    html += `<div><span class="font-medium">Speciality:</span> ${formData.get('speciality') || 'Not provided'}</div>`;
                    html += `<div><span class="font-medium">Phone:</span> ${formData.get('teacher_phone') || 'Not provided'}</div>`;
                    html += `<div><span class="font-medium">Date of Birth:</span> ${formData.get('date_of_birth') || 'Not provided'}</div>`;
                    html += `<div><span class="font-medium">Gender:</span> ${formData.get('gender') || 'Not selected'}</div>`;
                    html += `<div><span class="font-medium">Address:</span> ${formData.get('address') || 'Not provided'}</div>`;
                    html += `<div><span class="font-medium">Grade:</span> ${formData.get('grade') || 'Not provided'}</div>`;
                    html += '</div></div>';
                } else if (role === 'parent') {
                    html += '<div class="border-b pb-4">';
                    html += '<h4 class="text-lg font-semibold text-gray-900 mb-3">Parent Information</h4>';
                    html += '<div class="grid grid-cols-1 md:grid-cols-2 gap-4">';
                    html += `<div><span class="font-medium">Profession:</span> ${formData.get('profession') || 'Not provided'}</div>`;
                    html += `<div><span class="font-medium">Phone:</span> ${formData.get('phone') || 'Not provided'}</div>`;
                    html += '</div></div>';
                    
                    // Optional parent fields
                    const dateOfBirth = formData.get('date_of_birth');
                    const address = formData.get('address');
                    if (dateOfBirth || address) {
                        html += '<div class="border-b pb-4">';
                        html += '<h4 class="text-lg font-semibold text-gray-900 mb-3">Additional Information</h4>';
                        html += '<div class="grid grid-cols-1 md:grid-cols-2 gap-4">';
                        if (dateOfBirth) html += `<div><span class="font-medium">Date of Birth:</span> ${dateOfBirth}</div>`;
                        html += '</div>';
                        if (address) html += `<div class="mt-2"><span class="font-medium">Address:</span> ${address}</div>`;
                        html += '</div>';
                    }
                }
                
                html += '<div class="pt-4">';
                html += '<h4 class="text-lg font-semibold text-gray-900 mb-3">Account Security</h4>';
                html += '<div class="text-gray-600">Password will be securely stored</div>';
                html += '</div>';
                
                html += '</div>';
                previewContent.innerHTML = html;
            }
            
            // --- INITIALIZATION ---
            updateView();
        });

        function updateRequiredAttributes() {
            // Retire 'required' partout
            document.querySelectorAll('#register-form [required]').forEach(el => el.removeAttribute('required'));
            // Ajoute 'required' seulement sur les champs visibles de l'étape active
            document.querySelectorAll('.form-step.active [data-must-required]').forEach(el => {
                el.setAttribute('required', 'required');
            });
        }
    </script>
</body>
</html> 