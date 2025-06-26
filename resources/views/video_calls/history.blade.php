<x-app-layout>
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900 transition-colors duration-300">
        <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
            <!-- Header modernized -->
            <div class="flex items-center justify-between mb-8">
                <div class="flex items-center gap-4">
                    <span class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-gradient-to-tr from-gray-500 to-gray-600 shadow-lg">
                        <i class="fas fa-history text-white text-2xl"></i>
                    </span>
                    <div>
                        <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">Call History</h1>
                        <p class="text-gray-500 dark:text-gray-300">View your past video and audio calls</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('video-calls.index') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition duration-200">
                        <i class="fas fa-video mr-2"></i>
                        Active Calls
                    </a>
                    <a href="{{ route('video-calls.create') }}" class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition duration-200">
                        <i class="fas fa-plus mr-2"></i>
                        New Call
                    </a>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-gradient-to-br from-blue-100 to-blue-50 dark:from-blue-900 dark:to-blue-700 rounded-xl shadow-lg p-6 flex items-center gap-4">
                    <i class="fas fa-history text-blue-500 dark:text-blue-300 text-3xl"></i>
                    <div>
                        <div class="text-gray-500 dark:text-gray-300 text-sm">Total</div>
                        <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $videoCalls->total() }}</div>
                        <div class="text-blue-600 dark:text-blue-400 text-xs flex items-center gap-1">
                            <i class="fas fa-chart-line"></i> All calls
                        </div>
                    </div>
                </div>
                <div class="bg-gradient-to-br from-green-100 to-green-50 dark:from-green-900 dark:to-green-700 rounded-xl shadow-lg p-6 flex items-center gap-4">
                    <i class="fas fa-check text-green-500 dark:text-green-300 text-3xl"></i>
                    <div>
                        <div class="text-gray-500 dark:text-gray-300 text-sm">Completed</div>
                        <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $videoCalls->where('status', 'ended')->count() }}</div>
                        <div class="text-green-600 dark:text-green-400 text-xs flex items-center gap-1">
                            <i class="fas fa-check-circle"></i> Finished
                        </div>
                    </div>
                </div>
                <div class="bg-gradient-to-br from-red-100 to-red-50 dark:from-red-900 dark:to-red-700 rounded-xl shadow-lg p-6 flex items-center gap-4">
                    <i class="fas fa-times text-red-500 dark:text-red-300 text-3xl"></i>
                    <div>
                        <div class="text-gray-500 dark:text-gray-300 text-sm">Cancelled</div>
                        <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $videoCalls->where('status', 'cancelled')->count() }}</div>
                        <div class="text-red-600 dark:text-red-400 text-xs flex items-center gap-1">
                            <i class="fas fa-times-circle"></i> Cancelled
                        </div>
                    </div>
                </div>
                <div class="bg-gradient-to-br from-purple-100 to-purple-50 dark:from-purple-900 dark:to-purple-700 rounded-xl shadow-lg p-6 flex items-center gap-4">
                    <i class="fas fa-calendar-day text-purple-500 dark:text-purple-300 text-3xl"></i>
                    <div>
                        <div class="text-gray-500 dark:text-gray-300 text-sm">This Week</div>
                        <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $videoCalls->where('created_at', '>=', now()->subWeek())->count() }}</div>
                        <div class="text-purple-600 dark:text-purple-400 text-xs flex items-center gap-1">
                            <i class="fas fa-calendar-week"></i> Recent
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 mb-8">
                <form method="GET" class="flex flex-wrap gap-4">
                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Type</label>
                        <select name="type" id="type" class="rounded-lg border-gray-300 dark:bg-gray-700 dark:text-gray-100 dark:border-gray-600">
                            <option value="">All</option>
                            <option value="video" {{ request('type') == 'video' ? 'selected' : '' }}>Video</option>
                            <option value="audio" {{ request('type') == 'audio' ? 'selected' : '' }}>Audio</option>
                            <option value="both" {{ request('type') == 'both' ? 'selected' : '' }}>Video & Audio</option>
                        </select>
                    </div>
                    <div class="flex items-end">
                        <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg transition duration-200">
                            <i class="fas fa-filter mr-2"></i>
                            Filter
                        </button>
                    </div>
                </form>
            </div>

            <!-- Video Calls History List -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                <div class="overflow-x-auto">
                    @if($videoCalls->count() > 0)
                        <table class="min-w-full bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100">
                            <thead class="bg-gray-100 dark:bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Call</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Type</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Duration</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Participants</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Created by</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($videoCalls as $videoCall)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10">
                                                    <div class="h-10 w-10 rounded-full bg-gradient-to-r from-gray-400 to-gray-500 flex items-center justify-center">
                                                        <i class="fas fa-video text-white"></i>
                                                    </div>
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                        {{ $videoCall->title ?: 'Untitled Call' }}
                                                    </div>
                                                    @if($videoCall->description)
                                                        <div class="text-sm text-gray-500 dark:text-gray-400">
                                                            {{ Str::limit($videoCall->description, 50) }}
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                @if($videoCall->type == 'video') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                                                @elseif($videoCall->type == 'audio') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                                @else bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 @endif">
                                                @if($videoCall->type == 'video')
                                                    <i class="fas fa-video mr-1"></i> Video
                                                @elseif($videoCall->type == 'audio')
                                                    <i class="fas fa-microphone mr-1"></i> Audio
                                                @else
                                                    <i class="fas fa-video mr-1"></i> Video & Audio
                                                @endif
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                @if($videoCall->status == 'ended') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                                @else bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 @endif">
                                                @if($videoCall->status == 'ended')
                                                    <i class="fas fa-check mr-1"></i> Completed
                                                @else
                                                    <i class="fas fa-times mr-1"></i> Cancelled
                                                @endif
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">
                                            @if($videoCall->duration)
                                                {{ $videoCall->formatted_duration }}
                                            @else
                                                <span class="text-gray-400">--</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">
                                            {{ $videoCall->participants()->count() }} participant(s)
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">
                                            {{ $videoCall->initiator->name }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                            {{ $videoCall->created_at->format('d/m/Y H:i') }}
                                        </td>
                                        <td class="px-6 py-4 text-sm font-medium">
                                            <div class="flex space-x-2">
                                                <a href="{{ route('video-calls.show', $videoCall) }}" 
                                                   class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300"
                                                   title="View details">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                @if($videoCall->isHost(auth()->user()))
                                                    <form action="{{ route('video-calls.destroy', $videoCall) }}" method="POST" class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300"
                                                                title="Delete call" onclick="return confirm('Are you sure you want to delete this call?')">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="text-center py-12">
                            <div class="mx-auto w-24 h-24 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mb-4">
                                <i class="fas fa-history text-gray-400 dark:text-gray-500 text-3xl"></i>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No call history</h3>
                            <p class="text-gray-500 dark:text-gray-400 mb-6">
                                You haven't participated in any calls yet.
                            </p>
                            <a href="{{ route('video-calls.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                                <i class="fas fa-plus mr-2"></i>
                                New Call
                            </a>
                        </div>
                    @endif
                </div>

                <!-- Pagination -->
                @if($videoCalls->hasPages())
                    <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                        {{ $videoCalls->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout> 