<x-app-layout>
    <div class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl overflow-hidden">
            <!-- Header -->
            <div class="p-6 sm:p-8 bg-gradient-to-br from-indigo-500 to-purple-600">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <span class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-white/20">
                            <i class="fas fa-pencil-alt text-white text-xl"></i>
                        </span>
                <div>
                            <h1 class="text-2xl font-bold text-white tracking-tight">Edit Grade</h1>
                            <p class="text-purple-200 text-sm">
                                {{ $student_grade->student?->user->name }} - {{ $evaluation->subject?->name }} ({{ $evaluation->evaluationType?->name }})
                    </p>
                </div>
                </div>
                    <a href="{{ route('evaluations.student_grades.index', $evaluation) }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-white/10 text-white font-semibold shadow hover:bg-white/20 transition">
                        <i class="fas fa-arrow-left"></i> Back
                </a>
            </div>
        </div>

            <!-- Form -->
            <form method="POST" action="{{ route('student_grades.update', $student_grade) }}" class="p-6 sm:p-8 space-y-6">
            @csrf
            @method('PUT')
                
                @if ($errors->any())
                    <div class="p-4 rounded-lg bg-red-50 dark:bg-red-900/20 text-red-700 dark:text-red-300">
                        <p class="font-bold"><i class="fas fa-exclamation-triangle mr-2"></i>There were some problems with your input.</p>
                        <ul class="list-disc list-inside mt-2">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Student (Readonly) -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Student</label>
                        <input type="text" value="{{ $student_grade->student->user->name ?? 'N/A' }}" readonly class="w-full rounded-lg bg-gray-100 dark:bg-gray-700 border-0">
                    </div>

            <!-- Marks Obtained -->
                    <div>
                        <label for="marks_obtained" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Marks Obtained (out of {{ $evaluation->total_marks }})</label>
                        <input type="number" name="marks_obtained" id="marks_obtained" value="{{ old('marks_obtained', $student_grade->marks_obtained) }}" min="0" max="{{ $evaluation->total_marks }}" required class="w-full rounded-lg bg-gray-100 dark:bg-gray-700 border-0 focus:ring-2 focus:ring-indigo-500 transition">
                    </div>
            </div>

            <!-- Remarks -->
                <div>
                    <label for="remarks" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Remarks</label>
                    <textarea name="remarks" id="remarks" rows="4" class="w-full rounded-lg bg-gray-100 dark:bg-gray-700 border-0 focus:ring-2 focus:ring-indigo-500 transition">{{ old('remarks', $student_grade->remarks) }}</textarea>
            </div>

                <!-- Actions -->
                <div class="pt-6 border-t border-gray-200 dark:border-gray-700 flex justify-end gap-4">
                    <a href="{{ route('evaluations.student_grades.index', $evaluation) }}" class="px-6 py-2.5 rounded-lg bg-gray-200 dark:bg-gray-600 text-gray-700 dark:text-gray-200 font-semibold hover:bg-gray-300 dark:hover:bg-gray-500 transition">Cancel</a>
                    <button type="submit" class="px-6 py-2.5 rounded-lg bg-indigo-600 text-white font-semibold shadow hover:bg-indigo-700 transition flex items-center gap-2">
                        <i class="fas fa-save"></i> Update Grade
                </button>
            </div>
        </form>
    </div>
</div>
</x-app-layout>