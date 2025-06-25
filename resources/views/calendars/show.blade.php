<x-app-layout>
    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="flex items-center justify-between mb-8">
            <div class="flex items-center gap-4">
                <span class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-gradient-to-tr from-indigo-500 to-purple-600 shadow-lg">
                    <i class="fas fa-calendar-alt text-white text-2xl"></i>
                </span>
                <div>
                    <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">Schedule Details</h1>
                    <p class="text-gray-500 dark:text-gray-300">View schedule information</p>
                </div>
            </div>
            <div class="flex gap-3">
                @can('update', $calendar)
                <a href="{{ route('calendars.edit', $calendar) }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg bg-yellow-100 dark:bg-yellow-900 text-yellow-700 dark:text-yellow-200 font-semibold shadow hover:bg-yellow-200 dark:hover:bg-yellow-800 transition">
                    <i class="fas fa-edit"></i> Edit
                </a>
                @endcan
                <a href="{{ route('calendars.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 font-semibold shadow hover:bg-gray-200 dark:hover:bg-gray-700 transition">
                    <i class="fas fa-arrow-left"></i> Back to List
                </a>
            </div>
        </div>

        <!-- Content -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl overflow-hidden">
            <div class="p-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- School & Class Info -->
                    <div class="space-y-4">
                        <div class="flex items-center gap-3">
                            <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-gradient-to-tr from-indigo-500 to-purple-600">
                                <i class="fas fa-school text-white text-xl"></i>
                            </span>
                            <div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">School</div>
                                <div class="text-lg font-bold text-gray-900 dark:text-white">{{ $calendar->school->name ?? '-' }}</div>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-gradient-to-tr from-blue-500 to-cyan-600">
                                <i class="fas fa-chalkboard text-white text-xl"></i>
                            </span>
                            <div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">Class Room</div>
                                <div class="text-lg font-bold text-gray-900 dark:text-white">{{ $calendar->classRoom->name ?? '-' }}</div>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-gradient-to-tr from-pink-500 to-red-500">
                                <i class="fas fa-book text-white text-xl"></i>
                            </span>
                            <div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">Subject</div>
                                <div class="text-lg font-bold text-gray-900 dark:text-white">{{ $calendar->subject->name ?? '-' }}</div>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            @if(isset($calendar->teacher->user->profile_photo_url) && !str_contains($calendar->teacher->user->profile_photo_url, 'default-profile.png'))
                                <img src="{{ $calendar->teacher->user->profile_photo_url }}" alt="Teacher Photo" class="w-10 h-10 rounded-full object-cover border-2 border-yellow-400 shadow" />
                            @else
                                <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-gradient-to-tr from-yellow-400 to-yellow-600">
                                    <i class="fas fa-user-tie text-white text-xl"></i>
                                </span>
                            @endif
                            <div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">Teacher</div>
                                <div class="text-lg font-bold text-gray-900 dark:text-white">
                                    {{ $calendar->teacher->name ?? ($calendar->teacher->teacher_firstname ?? '') . ' ' . ($calendar->teacher->teacher_lastname ?? '') }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Schedule Details -->
                    <div class="space-y-4">
                        <div class="flex items-center gap-3">
                            <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-gradient-to-tr from-green-400 to-green-600">
                                <i class="fas fa-calendar-week text-white text-xl"></i>
                            </span>
                            <div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">Academic Year</div>
                                <div class="text-lg font-bold text-gray-900 dark:text-white">{{ $calendar->academic_year }}</div>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-gradient-to-tr from-purple-400 to-purple-600">
                                <i class="fas fa-hashtag text-white text-xl"></i>
                            </span>
                            <div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">Week Number</div>
                                <div class="text-lg font-bold text-gray-900 dark:text-white">{{ $calendar->week_number ?? '-' }}</div>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-gradient-to-tr from-indigo-400 to-indigo-600">
                                <i class="fas fa-calendar-day text-white text-xl"></i>
                            </span>
                            <div>
                                <div class="text-xs text-gray-500 dark:text-gray-400 font-semibold mb-2">Weekly Schedule</div>
                                <div class="grid grid-cols-1 gap-3">
                                    @php
                                        $days = ['monday','tuesday','wednesday','thursday','friday','saturday'];
                                        $icons = [
                                            'monday' => 'fa-moon',
                                            'tuesday' => 'fa-mars',
                                            'wednesday' => 'fa-venus',
                                            'thursday' => 'fa-jupiter',
                                            'friday' => 'fa-sun',
                                            'saturday' => 'fa-star',
                                        ];
                                        $colors = [
                                            'monday' => 'from-blue-400 to-blue-600',
                                            'tuesday' => 'from-green-400 to-green-600',
                                            'wednesday' => 'from-yellow-400 to-yellow-600',
                                            'thursday' => 'from-pink-400 to-pink-600',
                                            'friday' => 'from-purple-400 to-purple-600',
                                            'saturday' => 'from-red-400 to-red-600',
                                        ];
                                        $schedule = $calendar->week_schedule ?? [];
                                    @endphp
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        @foreach($days as $day)
                                            @if(!empty($schedule[$day]) && collect($schedule[$day])->filter(fn($slot) => $slot['start_time'] && $slot['end_time'])->count())
                                                <div class="rounded-xl shadow bg-gradient-to-tr {{ $colors[$day] }} p-4 text-white">
                                                    <div class="flex items-center gap-2 mb-2">
                                                        <i class="fas {{ $icons[$day] }}"></i>
                                                        <span class="capitalize font-bold">{{ $day }}</span>
                                                    </div>
                                                    <ul class="space-y-1">
                                                        @foreach($schedule[$day] as $slot)
                                                            @if($slot['start_time'] && $slot['end_time'])
                                                                <li class="flex items-center gap-2">
                                                                    <i class="fas fa-clock"></i>
                                                                    <span>{{ $slot['start_time'] }} - {{ $slot['end_time'] }}</span>
                                                                </li>
                                                            @endif
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Metadata -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mt-10">
                    <div class="space-y-3">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-user text-gray-400 dark:text-gray-500"></i>
                            <span class="text-xs text-gray-500 dark:text-gray-400">Created By</span>
                            <span class="font-semibold text-gray-900 dark:text-white">{{ $calendar->createdBy ? $calendar->createdBy->name : '-' }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i class="fas fa-calendar-plus text-gray-400 dark:text-gray-500"></i>
                            <span class="text-xs text-gray-500 dark:text-gray-400">Created At</span>
                            <span class="font-semibold text-gray-900 dark:text-white">{{ $calendar->created_at->format('F j, Y H:i') }}</span>
                        </div>
                    </div>
                    <div class="space-y-3">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-user-edit text-gray-400 dark:text-gray-500"></i>
                            <span class="text-xs text-gray-500 dark:text-gray-400">Updated By</span>
                            <span class="font-semibold text-gray-900 dark:text-white">{{ $calendar->updatedBy ? $calendar->updatedBy->name : '-' }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i class="fas fa-calendar-check text-gray-400 dark:text-gray-500"></i>
                            <span class="text-xs text-gray-500 dark:text-gray-400">Last Updated</span>
                            <span class="font-semibold text-gray-900 dark:text-white">{{ $calendar->updated_at->format('F j, Y H:i') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
