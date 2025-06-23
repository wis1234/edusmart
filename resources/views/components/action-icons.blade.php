@props(['viewRoute', 'editRoute' => null, 'deleteRoute' => null, 'canEdit' => false, 'canDelete' => false, 'deleteConfirmMessage' => 'Are you sure?'])

<div class="flex space-x-2 justify-center items-center">
    {{-- View Icon --}}
    <a href="{{ $viewRoute }}" class="group relative p-2 rounded-full text-blue-600 hover:text-white hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-400 transition-all duration-200 shadow-sm hover:scale-110" title="View">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12s3.75-7.5 9.75-7.5 9.75 7.5 9.75 7.5-3.75 7.5-9.75 7.5S2.25 12 2.25 12z" />
            <circle cx="12" cy="12" r="3" fill="currentColor" class="group-hover:fill-white transition" />
        </svg>
    </a>

    {{-- Edit Icon --}}
    @if($canEdit && $editRoute)
    <a href="{{ $editRoute }}" class="group relative p-2 rounded-full text-yellow-500 hover:text-white hover:bg-yellow-500 focus:outline-none focus:ring-2 focus:ring-yellow-300 transition-all duration-200 shadow-sm hover:scale-110" title="Edit">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 3.487a2.25 2.25 0 0 1 3.182 3.182l-12.225 12.225-4.5 1.5 1.5-4.5L16.862 3.487z" />
        </svg>
    </a>
    @endif

    {{-- Delete Icon --}}
    @if($canDelete && $deleteRoute)
    <form action="{{ $deleteRoute }}" method="POST" onsubmit="return confirm('{{ $deleteConfirmMessage }}');" class="inline-block">
        @csrf
        @method('DELETE')
        <button type="submit" class="group relative p-2 rounded-full text-red-600 hover:text-white hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-400 transition-all duration-200 shadow-sm hover:scale-110" title="Delete">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 7.5V6a2.25 2.25 0 0 1 2.25-2.25h7.5A2.25 2.25 0 0 1 18 6v1.5M4.5 7.5h15M9.75 11.25v4.5m4.5-4.5v4.5M6.75 7.5v9A2.25 2.25 0 0 0 9 18.75h6A2.25 2.25 0 0 0 17.25 16.5v-9" />
            </svg>
        </button>
    </form>
    @endif
</div>
