<div class="p-4 min-w-[300px] max-w-full">
    <h3 class="text-lg font-bold mb-4 text-gray-900 dark:text-white flex items-center gap-2">
        <i class="fas fa-info-circle text-indigo-500"></i> Activity Details
    </h3>
    <div class="flex items-center gap-3 mb-4">
        @if($activity->user && $activity->user->profile_photo)
            <a href="{{ asset('storage/' . $activity->user->profile_photo) }}" target="_blank" title="View full photo">
                <img src="{{ asset('storage/' . $activity->user->profile_photo) }}" alt="Profile Photo" class="w-12 h-12 rounded-full object-cover border-2 border-indigo-200 dark:border-indigo-600 shadow hover:scale-110 transition-transform cursor-pointer">
            </a>
        @else
            <div class="w-12 h-12 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                <i class="fas fa-user text-gray-400 text-2xl"></i>
            </div>
        @endif
        <div class="flex-1 min-w-0">
            <div class="font-semibold text-gray-900 dark:text-white">
                @if($activity->user)
                    {{ $activity->user->first_name }} {{ $activity->user->last_name }}
                @else
                    <span class="italic text-gray-400">System</span>
                @endif
            </div>
            @if($activity->user)
                <div class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ $activity->user->email }}</div>
            @endif
        </div>
    </div>
    <div class="mb-2 flex flex-wrap gap-2">
        <span class="font-semibold">Type:</span>
        <span class="inline-block px-2 py-1 rounded bg-gray-100 dark:bg-gray-700 text-sm">{{ ucfirst($activity->type) }}</span>
    </div>
    <div class="mb-2">
        <span class="font-semibold">Description:</span>
        <div class="text-gray-700 dark:text-gray-300 break-words">{{ $activity->description }}</div>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-2 mb-2">
        <div><span class="font-semibold">IP Address:</span> <span class="break-all">{{ $activity->ip_address }}</span></div>
        <div><span class="font-semibold">User Agent:</span> <span class="break-all">{{ $activity->user_agent }}</span></div>
    </div>
    <div class="mb-2">
        <span class="font-semibold">Date:</span> {{ $activity->created_at->format('Y-m-d H:i:s') }}
    </div>
    @if($activity->details)
        <div class="mb-2">
            <span class="font-semibold">Changes:</span>
            @php $details = $activity->getDetailsFormatted(); @endphp
            @if($details && !empty($details['changes']))
                <ul class="list-disc ml-6 text-gray-600 dark:text-gray-300">
                    @foreach($details['changes'] as $change)
                        @php $isPassword = Str::contains(Str::lower($change), 'password'); @endphp
                        @if($isPassword)
                            <li><span class="italic text-green-600">This user has reinforced the security of their account (password updated).</span></li>
                        @else
                            <li>{{ $change }}</li>
                        @endif
                    @endforeach
                </ul>
                <div class="mt-1 text-xs text-gray-400 dark:text-gray-500">
                    Changed by: {{ $details['changed_by'] }}
                </div>
            @else
                <div class="text-gray-400 italic">No detailed changes.</div>
            @endif
        </div>
    @endif
</div> 