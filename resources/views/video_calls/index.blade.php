<x-app-layout>
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900 transition-colors duration-300">
        <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
                <!-- Header -->
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Appels vidéo/audio</h1>
                        <a href="{{ route('video-calls.create') }}" class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition duration-200">
                            <i class="fas fa-video mr-2"></i>
                            Nouvel appel
                        </a>
                    </div>
                </div>

                <!-- Filters -->
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <form method="GET" class="flex flex-wrap gap-4">
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Statut</label>
                            <select name="status" id="status" class="rounded border-gray-300 dark:bg-gray-700 dark:text-gray-100">
                                <option value="">Tous</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>En attente</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Actif</option>
                                <option value="ended" {{ request('status') == 'ended' ? 'selected' : '' }}>Terminé</option>
                                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Annulé</option>
                            </select>
                        </div>
                        <div>
                            <label for="type" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Type</label>
                            <select name="type" id="type" class="rounded border-gray-300 dark:bg-gray-700 dark:text-gray-100">
                                <option value="">Tous</option>
                                <option value="video" {{ request('type') == 'video' ? 'selected' : '' }}>Vidéo</option>
                                <option value="audio" {{ request('type') == 'audio' ? 'selected' : '' }}>Audio</option>
                                <option value="both" {{ request('type') == 'both' ? 'selected' : '' }}>Vidéo & Audio</option>
                            </select>
                        </div>
                        <div class="flex items-end">
                            <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg transition duration-200">
                                <i class="fas fa-filter mr-2"></i>
                                Filtrer
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Video Calls List -->
                <div class="overflow-x-auto">
                    @if($videoCalls->count() > 0)
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Appel</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Type</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Statut</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Participants</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Créé par</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($videoCalls as $videoCall)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10">
                                                    <div class="h-10 w-10 rounded-full bg-gradient-to-r from-green-400 to-blue-500 flex items-center justify-center">
                                                        <i class="fas fa-video text-white"></i>
                                                    </div>
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                        {{ $videoCall->title ?: 'Appel sans titre' }}
                                                    </div>
                                                    @if($videoCall->description)
                                                        <div class="text-sm text-gray-500 dark:text-gray-400">
                                                            {{ Str::limit($videoCall->description, 50) }}
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                @if($videoCall->type == 'video') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                                                @elseif($videoCall->type == 'audio') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                                @else bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 @endif">
                                                @if($videoCall->type == 'video')
                                                    <i class="fas fa-video mr-1"></i> Vidéo
                                                @elseif($videoCall->type == 'audio')
                                                    <i class="fas fa-microphone mr-1"></i> Audio
                                                @else
                                                    <i class="fas fa-video mr-1"></i> Vidéo & Audio
                                                @endif
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                @if($videoCall->status == 'pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                                @elseif($videoCall->status == 'active') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                                @elseif($videoCall->status == 'ended') bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200
                                                @else bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 @endif">
                                                @if($videoCall->status == 'pending')
                                                    <i class="fas fa-clock mr-1"></i> En attente
                                                @elseif($videoCall->status == 'active')
                                                    <i class="fas fa-play mr-1"></i> Actif
                                                @elseif($videoCall->status == 'ended')
                                                    <i class="fas fa-stop mr-1"></i> Terminé
                                                @else
                                                    <i class="fas fa-times mr-1"></i> Annulé
                                                @endif
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                            {{ $videoCall->participants()->count() }} participant(s)
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                            {{ $videoCall->initiator->name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            {{ $videoCall->created_at->format('d/m/Y H:i') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex space-x-2">
                                                @if($videoCall->status == 'pending' && $videoCall->hasParticipant(auth()->user()))
                                                    <a href="{{ route('video-calls.join', $videoCall->room_id) }}" 
                                                       class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300"
                                                       title="Rejoindre l'appel">
                                                        <i class="fas fa-phone"></i>
                                                    </a>
                                                @endif
                                                @if($videoCall->status == 'active')
                                                    <a href="{{ route('video-calls.show', $videoCall->room_id) }}" 
                                                       class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300"
                                                       title="Voir l'appel">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                @endif
                                                @if($videoCall->isHost(auth()->user()) && $videoCall->status == 'pending')
                                                    <form method="POST" action="{{ route('video-calls.cancel', $videoCall->room_id) }}" class="inline">
                                                        @csrf
                                                        <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300"
                                                                title="Annuler l'appel" onclick="return confirm('Êtes-vous sûr de vouloir annuler cet appel ?')">
                                                            <i class="fas fa-times"></i>
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
                            <div class="mx-auto h-12 w-12 text-gray-400">
                                <i class="fas fa-video text-4xl"></i>
                            </div>
                            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">Aucun appel</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                Commencez par créer votre premier appel vidéo.
                            </p>
                            <div class="mt-6">
                                <a href="{{ route('video-calls.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                                    <i class="fas fa-plus mr-2"></i>
                                    Nouvel appel
                                </a>
                            </div>
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