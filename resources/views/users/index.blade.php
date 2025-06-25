<x-app-layout>
    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8" x-data="{ view: $persist('list').as('users_view') }">
        <!-- Header -->
        <div class="flex items-center justify-between mb-8">
            <div class="flex items-center gap-4">
                <span class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-gradient-to-tr from-indigo-500 to-purple-600 shadow-lg">
                    <i class="fas fa-users text-white text-2xl"></i>
                </span>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Users</h1>
            </div>
            <div class="flex items-center gap-2">
                <!-- View Toggles -->
                <button @click="view = 'list'" :class="{ 'bg-indigo-600 text-white': view === 'list', 'bg-gray-200 dark:bg-gray-700': view !== 'list' }" class="p-2 rounded-md focus:outline-none">
                    <i class="fas fa-list"></i>
                </button>
                <button @click="view = 'grid'" :class="{ 'bg-indigo-600 text-white': view === 'grid', 'bg-gray-200 dark:bg-gray-700': view !== 'grid' }" class="p-2 rounded-md focus:outline-none">
                    <i class="fas fa-th-large"></i>
                </button>
                <a href="{{ route('users.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 ml-4">Add User</a>
            </div>
        </div>

        <!-- Filters -->
        <form method="GET" action="{{ route('users.index') }}" class="mb-6 flex flex-wrap gap-4 items-end">
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Name or Email</label>
                <input type="text" name="search" id="search" value="{{ request('search') }}" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 focus:border-indigo-500 focus:ring-indigo-500" placeholder="Search...">
            </div>
            <div>
                <label for="role" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Role</label>
                <select name="role" id="role" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">All</option>
                    <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="school_admin" {{ request('role') == 'school_admin' ? 'selected' : '' }}>School Admin</option>
                    <option value="teacher" {{ request('role') == 'teacher' ? 'selected' : '' }}>Teacher</option>
                    <option value="parent" {{ request('role') == 'parent' ? 'selected' : '' }}>Parent</option>
                    <option value="student" {{ request('role') == 'student' ? 'selected' : '' }}>Student</option>
                </select>
            </div>
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
                <select name="status" id="status" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">All</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
            <div>
                <label for="validated" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Validated</label>
                <select name="validated" id="validated" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-900 focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">All</option>
                    <option value="1" {{ request('validated') === '1' ? 'selected' : '' }}>Yes</option>
                    <option value="0" {{ request('validated') === '0' ? 'selected' : '' }}>No</option>
                </select>
            </div>
            <div>
                <button type="submit" class="px-4 py-2 bg-indigo-500 text-white rounded hover:bg-indigo-600">Filter</button>
                <a href="{{ route('users.index') }}" class="ml-2 px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300">Reset</a>
            </div>
        </form>

        <!-- Content -->
        <div x-show="view === 'list'">
            @include('users.partials._list-view', ['users' => $users])
        </div>
        <div x-show="view === 'grid'" style="display: none;">
            @include('users.partials._grid-view', ['users' => $users])
        </div>
    </div>
</x-app-layout> 