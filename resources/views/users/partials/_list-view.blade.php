<div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
        <thead class="bg-gray-50 dark:bg-gray-700">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">First Name</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Last Name</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Role</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Validated</th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
            </tr>
        </thead>
        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
            @forelse($users as $user)
            <tr>
                <td class="px-6 py-4 whitespace-nowrap">{{ $user->first_name }}</td>
                <td class="px-6 py-4 whitespace-nowrap">{{ $user->last_name }}</td>
                <td class="px-6 py-4 whitespace-nowrap">{{ $user->email }}</td>
                <td class="px-6 py-4 whitespace-nowrap">{{ ucfirst($user->role) }}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                    @if($user->status === 'active')
                        <span class="px-2 py-1 rounded bg-green-100 text-green-800 text-xs font-semibold">Active</span>
                    @else
                        <span class="px-2 py-1 rounded bg-red-100 text-red-800 text-xs font-semibold">Inactive</span>
                    @endif
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    @if($user->validated)
                        <span class="px-2 py-1 rounded bg-green-100 text-green-800 text-xs font-semibold">Yes</span>
                    @else
                        <form action="{{ route('users.validate', $user) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="px-2 py-1 rounded bg-yellow-100 text-yellow-800 text-xs font-semibold hover:bg-yellow-200">Validate</button>
                        </form>
                    @endif
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right">
                    <x-action-icons 
                        :viewRoute="route('users.show', $user->id)"
                        :editRoute="auth()->user()->can('update', $user) ? route('users.edit', $user->id) : null"
                        :deleteRoute="auth()->user()->can('delete', $user) ? route('users.destroy', $user->id) : null"
                        :canEdit="auth()->user()->can('update', $user)"
                        :canDelete="auth()->user()->can('delete', $user)"
                        deleteConfirmMessage="Are you sure you want to delete this user?"
                    />
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="px-6 py-4 text-center text-gray-500">No users found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="p-4">
        {{ $users->appends(request()->except('page'))->links() }}
    </div>
</div> 