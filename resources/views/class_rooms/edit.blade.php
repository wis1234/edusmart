<x-app-layout>
    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="flex items-center justify-between mb-8">
            <div class="flex items-center gap-4">
                <span class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-gradient-to-tr from-indigo-500 to-purple-600 shadow-lg">
                    <i class="fas fa-door-open text-white text-2xl"></i>
                </span>
                <div>
                    <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">Edit Classroom</h1>
                    <p class="text-gray-500 dark:text-gray-300">Update classroom information</p>
                </div>
            </div>
            <a href="{{ route('class_rooms.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200 font-semibold hover:bg-gray-300 dark:hover:bg-gray-600 transition">
                <i class="fas fa-arrow-left"></i> Back to Classrooms
            </a>
        </div>

        <!-- Form Card -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-8">
            @if ($errors->any())
                <div class="mb-6 p-4 rounded-lg bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 border border-red-200 dark:border-red-800">
                    <div class="flex items-center gap-2 mb-2">
                        <i class="fas fa-exclamation-triangle"></i>
                        <span class="font-semibold">Please fix the following errors:</span>
                    </div>
                    <ul class="list-disc pl-5 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('class_rooms.update', $classRoom) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')
                
                <!-- Basic Information -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="name" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            Classroom Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="name" id="name" value="{{ old('name', $classRoom->name) }}" required
                            class="w-full px-4 py-3 rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100 border-0 focus:ring-2 focus:ring-indigo-500 transition"
                            placeholder="Ex: Maternelle 1">
                    </div>

                    <div>
                        <label for="school_id" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            School <span class="text-red-500">*</span>
                        </label>
                        <select name="school_id" id="school_id" required
                            class="w-full px-4 py-3 rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100 border-0 focus:ring-2 focus:ring-indigo-500 transition">
                            <option value="">Select School</option>
                            @foreach($schools as $school)
                                <option value="{{ $school->id }}" {{ old('school_id', $classRoom->school_id) == $school->id ? 'selected' : '' }}>
                                    {{ $school->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="grade_level" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            Grade Level <span class="text-red-500">*</span>
                        </label>
                        <select name="grade_level" id="grade_level" required
                            class="w-full px-4 py-3 rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100 border-0 focus:ring-2 focus:ring-indigo-500 transition">
                            <option value="">Select Grade Level</option>
                            <option value="Maternelle" {{ old('grade_level', $classRoom->grade_level) == 'Maternelle' ? 'selected' : '' }}>Maternelle</option>
                            <option value="Primaire" {{ old('grade_level', $classRoom->grade_level) == 'Primaire' ? 'selected' : '' }}>Primaire</option>
                            <option value="Secondaire" {{ old('grade_level', $classRoom->grade_level) == 'Secondaire' ? 'selected' : '' }}>Secondaire</option>
                            <option value="Lycée" {{ old('grade_level', $classRoom->grade_level) == 'Lycée' ? 'selected' : '' }}>Lycée</option>
                            <option value="Université" {{ old('grade_level', $classRoom->grade_level) == 'Université' ? 'selected' : '' }}>Université</option>
                        </select>
                    </div>

                    <div>
                        <label for="section" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            Section <span class="text-red-500">*</span>
                        </label>
                        <select name="section" id="section" required
                            class="w-full px-4 py-3 rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100 border-0 focus:ring-2 focus:ring-indigo-500 transition">
                            <option value="">Select Section</option>
                            <option value="Commun" {{ old('section', $classRoom->section) == 'Commun' ? 'selected' : '' }}>TC (Tronc Commun)</option>
                            <option value="A" {{ old('section', $classRoom->section) == 'A' ? 'selected' : '' }}>Section A (Littéraire)</option>
                            <option value="B" {{ old('section', $classRoom->section) == 'B' ? 'selected' : '' }}>Section B (Économique)</option>
                            <option value="C" {{ old('section', $classRoom->section) == 'C' ? 'selected' : '' }}>Section C (Scientifique - Maths/Physique)</option>
                            <option value="D" {{ old('section', $classRoom->section) == 'D' ? 'selected' : '' }}>Section D (Scientifique - Bio/Physique)</option>
                            <option value="E" {{ old('section', $classRoom->section) == 'E' ? 'selected' : '' }}>Section E (Technique Industrielle)</option>
                            <option value="F" {{ old('section', $classRoom->section) == 'F' ? 'selected' : '' }}>Section F (Technique Commerciale)</option>
                            <option value="G" {{ old('section', $classRoom->section) == 'G' ? 'selected' : '' }}>Section G (Gestion)</option>
                        </select>
                    </div>

                    <div>
                        <label for="academic_year" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            Academic Year <span class="text-red-500">*</span>
                        </label>
                        <select name="academic_year" id="academic_year" required
                            class="w-full px-4 py-3 rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100 border-0 focus:ring-2 focus:ring-indigo-500 transition">
                            <option value="">Select Academic Year</option>
                            @php
                                $currentYear = date('Y');
                                $startYear = $currentYear - 2;
                                $endYear = $currentYear + 8;
                            @endphp
                            @for ($year = $startYear; $year <= $endYear; $year++)
                                @php $academic = $year . '-' . ($year + 1); @endphp
                                <option value="{{ $academic }}" {{ old('academic_year', $classRoom->academic_year) == $academic ? 'selected' : '' }}>
                                    {{ $academic }}
                                </option>
                            @endfor
                        </select>
                    </div>

                    <div>
                        <label for="capacity" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            Capacity <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="capacity" id="capacity" value="{{ old('capacity', $classRoom->capacity) }}" required min="1"
                            class="w-full px-4 py-3 rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100 border-0 focus:ring-2 focus:ring-indigo-500 transition"
                            placeholder="e.g., 30">
                    </div>
                </div>

                <!-- Schedule Information -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="start_time" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            Start Time <span class="text-red-500">*</span>
                        </label>
                        <input type="time" name="start_time" id="start_time" value="{{ old('start_time', $classRoom->start_time ? $classRoom->start_time->format('H:i') : '') }}" required
                            class="w-full px-4 py-3 rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100 border-0 focus:ring-2 focus:ring-indigo-500 transition">
                    </div>

                    <div>
                        <label for="end_time" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            End Time <span class="text-red-500">*</span>
                        </label>
                        <input type="time" name="end_time" id="end_time" value="{{ old('end_time', $classRoom->end_time ? $classRoom->end_time->format('H:i') : '') }}" required
                            class="w-full px-4 py-3 rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100 border-0 focus:ring-2 focus:ring-indigo-500 transition">
                    </div>

                    <div>
                        <label for="days_of_week" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            Days of Week <span class="text-red-500">*</span>
                        </label>
                        <select name="days_of_week[]" id="days_of_week" multiple required
                            class="w-full px-4 py-3 rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100 border-0 focus:ring-2 focus:ring-indigo-500 transition">
                            @foreach(['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'] as $day)
                                <option value="{{ $day }}" {{ (collect(old('days_of_week', $classRoom->days_of_week))->contains($day)) ? 'selected' : '' }}>
                                    {{ ucfirst($day) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="room_number" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            Room Number <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="room_number" id="room_number" value="{{ old('room_number', $classRoom->room_number) }}" required
                            class="w-full px-4 py-3 rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100 border-0 focus:ring-2 focus:ring-indigo-500 transition"
                            placeholder="e.g., 101">
                    </div>

                    <div>
                        <label for="building" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            Building <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="building" id="building" value="{{ old('building', $classRoom->building) }}" required
                            class="w-full px-4 py-3 rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100 border-0 focus:ring-2 focus:ring-indigo-500 transition"
                            placeholder="e.g., Building A">
                    </div>

                    <div>
                        <label for="floor" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            Floor <span class="text-red-500">*</span>
                        </label>
                        <select name="floor" id="floor" required
                            class="w-full px-4 py-3 rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100 border-0 focus:ring-2 focus:ring-indigo-500 transition">
                            <option value="">Select a floor</option>
                            @php
                                $floors = [
                                    0 => 'Rez-de-chaussée',
                                    1 => 'Batiment',
                                    2 => '1er étage',
                                    3 => '2e étage',        
                                    4 => '3e étage',
                                    5 => '4e étage',
                                    6 => '5e étage',
                                    7 => '6e étage',
                                ];
                            @endphp
                            @foreach($floors as $key => $label)
                                <option value="{{ $key }}" {{ old('floor', $classRoom->floor) == $key ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Active Status -->
                <div class="flex items-center">
                    <input type="hidden" name="is_active" value="0">
                    <div class="flex items-center h-5">
                        <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $classRoom->is_active) ? 'checked' : '' }}
                            class="w-4 h-4 text-indigo-600 bg-gray-100 border-gray-300 rounded focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                    </div>
                    <label for="is_active" class="ml-2 text-sm font-semibold text-gray-700 dark:text-gray-300">
                        Active Classroom
                    </label>
                </div>

                <!-- Submit Buttons -->
                <div class="flex items-center justify-end gap-4 pt-6 border-t border-gray-200 dark:border-gray-700">
                    <a href="{{ route('class_rooms.index') }}" 
                        class="px-6 py-3 rounded-lg bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200 font-semibold hover:bg-gray-300 dark:hover:bg-gray-600 transition">
                        Cancel
                    </a>
                    <button type="submit" 
                        class="inline-flex items-center gap-2 px-6 py-3 rounded-lg bg-indigo-600 text-white font-semibold shadow hover:bg-indigo-700 transition">
                        <i class="fas fa-save"></i> Update Classroom
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
