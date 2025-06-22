@php /** @var \App\Models\School $school */ @endphp
<!-- Host Management Section -->
<div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 mb-8">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-lg font-bold flex items-center gap-2"><i class="fas fa-users-cog text-indigo-500"></i> Host Management</h2>
        <span class="inline-flex items-center px-2 py-1 rounded text-xs font-bold bg-indigo-100 text-indigo-700 dark:bg-indigo-900 dark:text-indigo-300">{{ $school->hosts->count() }} Hosts</span>
    </div>
    @if(session('success'))
        <div class="mb-6 p-4 rounded-lg bg-green-50 border border-green-200 text-green-800 flex items-center gap-3">
            <i class="fas fa-check-circle"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif
    @if($errors->any())
        <div class="mb-6 p-4 rounded-lg bg-red-50 border border-red-200 text-red-800">
            <div class="font-bold mb-2 flex items-center gap-2"><i class="fas fa-exclamation-triangle"></i> Please fix the errors below.</div>
            <ul class="list-disc list-inside pl-4">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        @if(Auth::user()->hasRole('admin') || Auth::user()->role === 'admin')
        <div class="lg:col-span-1">
            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4 text-gray-800 dark:text-gray-100 flex items-center gap-2">
                    <i class="fas fa-plus-circle text-indigo-500"></i> Add New Host
                </h3>
                <form action="{{ route('schools.hosts.store', $school) }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label for="first_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">First Name</label>
                        <input type="text" name="first_name" id="first_name" required 
                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 focus:ring-indigo-500 focus:border-indigo-500" 
                            value="{{ old('first_name') }}">
                    </div>
                    <div>
                        <label for="last_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Last Name</label>
                        <input type="text" name="last_name" id="last_name" required 
                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 focus:ring-indigo-500 focus:border-indigo-500" 
                            value="{{ old('last_name') }}">
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email</label>
                        <input type="email" name="email" id="email" required 
                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 focus:ring-indigo-500 focus:border-indigo-500" 
                            value="{{ old('email') }}">
                    </div>
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Password</label>
                        <input type="password" name="password" id="password" required 
                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Confirm Password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" required 
                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <input type="hidden" name="school_id" value="{{ $school->id }}">
                    <button type="submit" class="w-full flex justify-center items-center gap-2 px-4 py-2 rounded-lg bg-indigo-600 text-white font-semibold hover:bg-indigo-700 transition">
                        <i class="fas fa-plus-circle"></i> Create Host
                    </button>
                </form>
            </div>
        </div>
        @endif
        <div class="{{ (Auth::user()->hasRole('admin') || Auth::user()->role === 'admin') ? 'lg:col-span-2' : 'lg:col-span-3' }}">
            <h3 class="text-lg font-semibold mb-4 text-gray-800 dark:text-gray-100 flex items-center gap-2">
                <i class="fas fa-users text-indigo-500"></i> Current Hosts
            </h3>
            @if($school->hosts->count() > 0)
                <div class="space-y-3">
                    @foreach($school->hosts as $host)
                        <div class="flex items-center bg-white dark:bg-gray-700 p-4 rounded-lg shadow-sm border border-gray-200 dark:border-gray-600 hover:shadow-md transition-shadow">
                            <img class="w-12 h-12 rounded-full mr-4 border-2 border-indigo-400 object-cover" 
                                 src="{{ $host->profile_photo ? asset('storage/' . $host->profile_photo) : 'https://ui-avatars.com/api/?name=' . urlencode($host->first_name . ' ' . $host->last_name) . '&color=FFFFFF&background=818cf8' }}" 
                                 alt="{{ $host->first_name }}'s Avatar">
                            <div class="flex-grow">
                                <p class="font-semibold text-gray-900 dark:text-white">{{ $host->first_name }} {{ $host->last_name }}</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400 flex items-center gap-2">
                                    <i class="fas fa-envelope"></i>
                                    <span>{{ $host->email }}</span>
                                </p>
                                <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">
                                    Host since: {{ $host->created_at->format('M d, Y') }}
                                </p>
                            </div>
                            @can('delete', $host)
                            <form action="{{ route('schools.hosts.destroy', ['school' => $school, 'host' => $host]) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this host?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-gray-400 hover:text-red-500 dark:hover:text-red-400 p-2 rounded-full transition-colors duration-200">
                                    <i class="fas fa-trash-alt"></i>
                                    <span class="sr-only">Delete Host</span>
                                </button>
                            </form>
                            @endcan
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8 px-4 border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 dark:bg-gray-700 mb-4">
                        <i class="fas fa-user-slash text-2xl text-gray-400"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">No hosts found for this school.</h3>
                    <p class="text-gray-500 dark:text-gray-400 mb-6">Start by creating the first host for this school.</p>
                    <p class="text-gray-400 dark:text-gray-500 text-sm">Use the form on the left to add the first host.</p>
                </div>
            @endif
        </div>
    </div>
</div> 