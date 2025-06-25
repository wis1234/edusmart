<x-app-layout>
    <div class="max-w-5xl mx-auto py-10 px-4">
        @if(!$query)
            <div class="flex flex-col items-center justify-center py-20">
                <span class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-gradient-to-tr from-indigo-100 to-purple-200 dark:from-indigo-900 dark:to-purple-900 shadow-lg mb-4">
                    <i class="fas fa-search text-indigo-400 dark:text-indigo-300 text-4xl"></i>
                </span>
                <div class="text-2xl font-bold text-indigo-700 dark:text-indigo-200 mb-2">Start your search</div>
                <div class="text-gray-500 dark:text-gray-400 text-lg">Type a keyword in the search bar above to explore the entire platform.<br>Find users, students, teachers, parents, schools, and more!</div>
            </div>
        @endif
        @php
            $isAdmin = auth()->user() && (method_exists(auth()->user(), 'isAdmin') ? auth()->user()->isAdmin() : false);
        @endphp
        @if($query && empty($results))
            <div class="flex flex-col items-center justify-center py-16">
                <span class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-gradient-to-tr from-gray-200 to-gray-400 dark:from-gray-700 dark:to-gray-800 shadow-lg mb-4">
                    <i class="fas fa-search-minus text-gray-500 dark:text-gray-300 text-4xl"></i>
                </span>
                <div class="text-2xl font-bold text-gray-700 dark:text-gray-200 mb-2">No results found</div>
                <div class="text-gray-500 dark:text-gray-400 text-lg">We couldn't find anything for <span class="font-semibold">"{{ $query }}"</span>. <br>Try another keyword or check your spelling.</div>
            </div>
        @endif
        @if($query && !empty($results))
            <div class="flex flex-col items-center justify-center mb-8">
                <span class="inline-flex items-center justify-center w-14 h-14 rounded-full bg-gradient-to-tr from-indigo-200 to-purple-200 dark:from-indigo-900 dark:to-purple-900 shadow mb-2">
                    <i class="fas fa-list-alt text-indigo-500 dark:text-indigo-300 text-2xl"></i>
                </span>
                <div class="text-xl font-bold text-indigo-700 dark:text-indigo-200">Here are your search results</div>
                <div class="text-gray-500 dark:text-gray-400 text-base">We found the following matches for <span class="font-semibold">"{{ $query }}"</span>.</div>
            </div>
        @endif
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 justify-center items-start">
        @foreach($results as $type => $items)
            @php
                $icons = [
                    'Users' => 'fa-user',
                    'Students' => 'fa-user-graduate',
                    'Teachers' => 'fa-chalkboard-teacher',
                    'Parents' => 'fa-users',
                    'Guardians' => 'fa-shield-alt',
                    'Schools' => 'fa-school',
                    'Subjects' => 'fa-book',
                    'ClassRooms' => 'fa-door-open',
                    'Evaluations' => 'fa-tasks',
                ];
                $colors = [
                    'Users' => 'from-indigo-500 to-purple-600',
                    'Students' => 'from-blue-500 to-cyan-600',
                    'Teachers' => 'from-yellow-500 to-yellow-700',
                    'Parents' => 'from-pink-500 to-pink-700',
                    'Guardians' => 'from-green-500 to-green-700',
                    'Schools' => 'from-indigo-400 to-indigo-700',
                    'Subjects' => 'from-purple-500 to-purple-700',
                    'ClassRooms' => 'from-cyan-500 to-blue-700',
                    'Evaluations' => 'from-orange-500 to-orange-700',
                ];
            @endphp
            @if($type !== 'Users' || $isAdmin)
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-6 flex flex-col items-center">
                <div class="flex items-center gap-3 mb-4">
                    <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-gradient-to-tr {{ $colors[$type] ?? 'from-gray-400 to-gray-600' }} shadow-lg">
                        <i class="fas {{ $icons[$type] ?? 'fa-search' }} text-white text-xl"></i>
                    </span>
                    <h2 class="text-xl font-bold text-indigo-700 dark:text-indigo-300">{{ $type }}</h2>
                </div>
                <ul class="divide-y divide-gray-100 dark:divide-gray-700 w-full">
                    @foreach($items as $item)
                        <li class="py-3 px-2 transition-all duration-200 rounded-lg hover:bg-indigo-50 dark:hover:bg-indigo-900/40 hover:shadow-lg hover:-translate-y-0.5 flex items-center gap-2 justify-center">
                            @if($type === 'Users')
                                <i class="fas fa-user text-indigo-400"></i>
                                @if($isAdmin)
                                    <a href="{{ $item->id === auth()->id() ? route('profile.edit') : '#' }}" class="hover:underline text-indigo-600 dark:text-indigo-300 font-semibold">{{ $item->first_name }} {{ $item->last_name }}</a>
                                @else
                                    <a href="{{ $item->id === auth()->id() ? route('profile.edit') : '#' }}" class="hover:underline text-indigo-600 dark:text-indigo-300 font-semibold">{{ $item->first_name }} {{ $item->last_name }}</a>
                                @endif
                            @elseif($type === 'Students')
                                <i class="fas fa-user-graduate text-blue-400"></i>
                                <a href="{{ route('students.show', $item->id) }}" class="hover:underline text-blue-600 dark:text-blue-300 font-semibold">{{ $item->first_name }} {{ $item->last_name }}</a>
                            @elseif($type === 'Teachers')
                                <i class="fas fa-chalkboard-teacher text-yellow-500"></i>
                                <a href="{{ route('teachers.show', $item->id) }}" class="hover:underline text-yellow-700 dark:text-yellow-300 font-semibold">{{ $item->teacher_firstname }} {{ $item->teacher_lastname }}</a>
                            @elseif($type === 'Parents')
                                <i class="fas fa-users text-pink-500"></i>
                                <a href="{{ route('parents.show', $item->user->id) }}" class="font-semibold hover:underline text-pink-700 dark:text-pink-300">{{ $item->user->first_name ?? '' }} {{ $item->user->last_name ?? '' }}</a>
                            @elseif($type === 'Guardians')
                                <i class="fas fa-shield-alt text-green-500"></i>
                                <a href="{{ route('guardians.show', $item->user->id) }}" class="font-semibold hover:underline text-green-700 dark:text-green-300">{{ $item->user->first_name ?? '' }} {{ $item->user->last_name ?? '' }}</a>
                            @elseif($type === 'Schools')
                                <i class="fas fa-school text-indigo-500"></i>
                                <a href="{{ route('schools.show', $item->id) }}" class="hover:underline text-indigo-700 dark:text-indigo-300 font-semibold">{{ $item->name }}</a>
                            @elseif($type === 'Subjects')
                                <i class="fas fa-book text-purple-500"></i>
                                <a href="{{ route('subjects.show', $item->id) }}" class="hover:underline text-purple-700 dark:text-purple-300 font-semibold">{{ $item->name }}</a>
                            @elseif($type === 'ClassRooms')
                                <i class="fas fa-door-open text-cyan-500"></i>
                                <a href="{{ route('class_rooms.show', $item->id) }}" class="hover:underline text-cyan-700 dark:text-cyan-300 font-semibold">{{ $item->name }}</a>
                            @elseif($type === 'Evaluations')
                                <i class="fas fa-tasks text-orange-500"></i>
                                <span class="font-semibold">{{ $item->subject->name ?? '' }}</span>
                                <span class="text-gray-500 ml-2">{{ $item->classRoom->name ?? '' }}</span>
                                <span class="text-gray-500 ml-2">{{ $item->academic_year ?? '' }} - {{ $item->term ?? '' }}</span>
                                <span class="text-gray-400 ml-2">{{ Str::limit($item->notes, 40) }}</span>
                            @endif
                        </li>
                    @endforeach
                </ul>
            </div>
            @endif
        @endforeach
        </div>
    </div>
</x-app-layout> 