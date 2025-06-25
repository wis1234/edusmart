<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
    @forelse($users as $user)
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 flex flex-col items-center">
            <img src="{{ $user->profile_photo ? asset('storage/' . $user->profile_photo) : 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&background=4f46e5&color=fff' }}" 
                 alt="{{ $user->name }}" 
                 class="w-20 h-20 rounded-full object-cover border-4 border-indigo-200 dark:border-indigo-800 shadow mb-4">
            <div class="text-center">
                <h2 class="text-lg font-bold text-gray-900 dark:text-white">{{ $user->first_name }} {{ $user->last_name }}</h2>
                <p class="text-sm text-indigo-600 dark:text-indigo-400 mb-2">{{ $user->email }}</p>
                <span class="inline-block px-2 py-1 rounded text-xs font-semibold mb-1
                    @switch($user->role)
                        @case('admin') bg-red-100 text-red-800 @break
                        @case('school_admin') bg-purple-100 text-purple-800 @break
                        @case('teacher') bg-blue-100 text-blue-800 @break
                        @case('parent') bg-yellow-100 text-yellow-800 @break
                        @case('student') bg-green-100 text-green-800 @break
                    @endswitch
                ">{{ ucfirst($user->role) }}</span>
                <div class="mt-2">
                    @if($user->status === 'active')
                        <span class="px-2 py-1 rounded bg-green-100 text-green-800 text-xs font-semibold">Active</span>
                    @else
                        <span class="px-2 py-1 rounded bg-red-100 text-red-800 text-xs font-semibold">Inactive</span>
                    @endif
                </div>
                <div class="mt-2">
                    @if($user->validated)
                        <span class="px-2 py-1 rounded bg-green-100 text-green-800 text-xs font-semibold">Validated</span>
                    @else
                        <form action="{{ route('users.validate', $user) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="px-2 py-1 rounded bg-yellow-100 text-yellow-800 text-xs font-semibold hover:bg-yellow-200">Validate</button>
                        </form>
                    @endif
                </div>
            </div>
            <div class="mt-4 flex gap-2">
                <x-action-icons 
                    :viewRoute="route('users.show', $user->id)"
                    :editRoute="auth()->user()->can('update', $user) ? route('users.edit', $user->id) : null"
                    :deleteRoute="auth()->user()->can('delete', $user) ? route('users.destroy', $user->id) : null"
                    :canEdit="auth()->user()->can('update', $user)"
                    :canDelete="auth()->user()->can('delete', $user)"
                    deleteConfirmMessage="Are you sure you want to delete this user?"
                />
            </div>
        </div>
    @empty
        <div class="col-span-full text-center text-gray-500">No users found.</div>
    @endforelse
</div>
<div class="p-4">
    {{ $users->appends(request()->except('page'))->links() }} 