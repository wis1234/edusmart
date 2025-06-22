<x-app-layout>
    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl overflow-hidden">
            <!-- Header -->
            <div class="p-6 sm:p-8 bg-gradient-to-br from-indigo-500 to-purple-600">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <span class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-white/20">
                            <i class="fas fa-plus text-white text-xl"></i>
                        </span>
                        <div>
                            <h1 class="text-2xl font-bold text-white tracking-tight">Add New Grade</h1>
                            <p class="text-purple-200 text-sm">For evaluation: {{ $evaluation->subject->name }} - {{ $evaluation->evaluationType->name }}</p>
                        </div>
                    </div>
                    <a href="{{ route('evaluations.student_grades.index', $evaluation) }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-white/10 text-white font-semibold shadow hover:bg-white/20 transition">
                        <i class="fas fa-arrow-left"></i> Back
                    </a>
                </div>
            </div>

            <!-- Grade Entry Row: Panel + Fields Side by Side -->
            <form method="POST" action="{{ route('evaluations.student_grades.store', $evaluation) }}" class="p-6 sm:p-8">
                @csrf
                
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
                
                <div class="flex flex-col md:flex-row md:items-center md:gap-8 my-8">
                    <!-- Visual Grade Panel -->
                    <div class="flex justify-center md:justify-start md:w-1/3 mb-6 md:mb-0">
                        <div class="relative transition-all duration-300 ease-in-out shadow-2xl rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-600 p-8 flex flex-col items-center w-full max-w-md">
                            <span class="text-white text-lg font-semibold mb-2">Entered Grade</span>
                            <span id="note-preview"
                                class="text-7xl font-extrabold text-white drop-shadow-lg transition-all duration-300 ease-in-out animate-pulse"
                            >{{ old('marks_obtained', 0) }}</span>
                            <span class="text-white/80 text-md mt-2">/ {{ $evaluation->total_marks }}</span>
                            <span id="grade-emoji" class="absolute top-2 right-4 text-5xl select-none" style="display:none;"></span>
                        </div>
                    </div>
                    <!-- Fields -->
                    <div class="flex-1 grid grid-cols-1 gap-6">
                        <!-- Student -->
                        <div>
                            <label for="student_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Student</label>
                            <select name="student_id" id="student_id" required class="w-full rounded-lg bg-gray-100 dark:bg-gray-700 border-0 focus:ring-2 focus:ring-indigo-500 transition">
                                @forelse ($students as $student)
                                    <option value="{{ $student->id }}" @selected(old('student_id', $students->first()?->id) == $student->id)>
                                        {{ $student->first_name }} {{ $student->last_name }} ({{ $student->admission_number }})
                                    </option>
                                @empty
                                    <option disabled>No students available to grade for this evaluation.</option>
                                @endforelse
                            </select>
                            @if($students->isEmpty())
                                <p class="text-sm text-red-600 dark:text-red-400 mt-1">
                                    No students found in class {{ $evaluation->classRoom->name }} for school {{ $evaluation->subject->school->name ?? $evaluation->classRoom->school->name }}.
                                </p>
                            @endif
                        </div>
                        <!-- Marks Obtained -->
                        <div>
                            <label for="marks_obtained" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Marks Obtained (out of {{ $evaluation->total_marks }})</label>
                            <input type="number" name="marks_obtained" id="marks-obtained" value="{{ old('marks_obtained') }}" min="0" max="{{ $evaluation->total_marks }}" required class="w-full rounded-lg bg-gray-100 dark:bg-gray-700 border-0 focus:ring-2 focus:ring-indigo-500 transition">
                        </div>
                    </div>
                </div>

                <!-- Remarks -->
                <div class="mb-6">
                    <label for="remarks" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Remarks</label>
                    <textarea name="remarks" id="remarks" rows="2" class="w-full rounded-lg bg-gray-100 dark:bg-gray-700 border-0 focus:ring-2 focus:ring-indigo-500 transition">{{ old('remarks') }}</textarea>
                </div>

                <!-- Actions -->
                <div class="pt-6 border-t border-gray-200 dark:border-gray-700 flex justify-end gap-4">
                    <a href="{{ route('evaluations.student_grades.index', $evaluation) }}" class="px-6 py-2.5 rounded-lg bg-gray-200 dark:bg-gray-600 text-gray-700 dark:text-gray-200 font-semibold hover:bg-gray-300 dark:hover:bg-gray-500 transition">Cancel</a>
                    <button type="submit" class="px-6 py-2.5 rounded-lg bg-indigo-600 text-white font-semibold shadow hover:bg-indigo-700 transition flex items-center gap-2">
                        <i class="fas fa-save"></i> Save Grade
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const input = document.getElementById('marks-obtained');
        const preview = document.getElementById('note-preview');
        const remarks = document.getElementById('remarks');
        const emoji = document.getElementById('grade-emoji');
        let userEditedRemarks = false;
        let emojiTimeout = null;

        // Auto focus on grade input
        if(input) {
            input.focus();
        }

        // Generate automatic remark text in English
        function generateRemark(val, max) {
            val = Number(val);
            max = Number(max);
            if(isNaN(val) || val === 0) return '';
            const percent = (val / max) * 100;
            if(percent >= 90) return "Excellent work! Keep it up.";
            if(percent >= 75) return "Very good result, well done!";
            if(percent >= 60) return "Good effort, you can improve even more.";
            if(percent >= 40) return "Average result, keep working!";
            return "Needs improvement, don't give up!";
        }

        // Emoji by grade
        function getEmoji(val, max) {
            val = Number(val);
            max = Number(max);
            if(isNaN(val) || val === 0) return '';
            const percent = (val / max) * 100;
            if(percent >= 90) return '🏆';
            if(percent >= 75) return '😊';
            if(percent >= 60) return '🙂';
            if(percent >= 40) return '😐';
            return '🚩';
        }

        // Detect if user edits remarks manually
        if(remarks) {
            remarks.addEventListener('input', function() {
                userEditedRemarks = true;
            });
        }

        function showEmoji(val, max) {
            if(!emoji) return;
            const emj = getEmoji(val, max);
            if(emj) {
                emoji.textContent = emj;
                emoji.style.display = '';
                if(emojiTimeout) clearTimeout(emojiTimeout);
                emojiTimeout = setTimeout(() => {
                    emoji.style.display = 'none';
                }, 1500);
            } else {
                emoji.style.display = 'none';
            }
        }

        if(input && preview) {
            input.addEventListener('input', function() {
                let val = input.value;
                if(val === '' || isNaN(val)) val = 0;
                preview.textContent = val;
                showEmoji(val, input.max);
                // Update remarks only if user hasn't edited manually
                if(remarks && !userEditedRemarks) {
                    remarks.value = generateRemark(val, input.max);
                }
            });
            // Trigger generation on load if a grade exists
            if(input.value && remarks && !userEditedRemarks) {
                remarks.value = generateRemark(input.value, input.max);
            }
        }
    });
</script>