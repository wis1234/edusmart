@props(['viewRoute', 'editRoute' => null, 'deleteRoute' => null, 'canEdit' => false, 'canDelete' => false, 'deleteConfirmMessage' => 'Are you sure?'])

<div class="flex space-x-3 justify-center items-center">
    {{-- View Icon --}}
    <a href="{{ $viewRoute }}" class="text-blue-600 hover:text-blue-800" title="View">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
            <path d="M10 3C5.455 3 1.737 6.264.458 10c1.28 3.736 4.997 7 9.542 7s8.263-3.264 9.542-7c-1.28-3.736-4.997-7-9.542-7zm0 11a4 4 0 110-8 4 4 0 010 8z"/>
        </svg>
    </a>

    {{-- Edit Icon --}}
    @if($canEdit && $editRoute)
    <a href="{{ $editRoute }}" class="text-yellow-500 hover:text-yellow-700" title="Edit">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
            <path d="M17.414 2.586a2 2 0 010 2.828L8.414 14.414a1 1 0 01-.293.207l-4 2a1 1 0 01-1.32-1.32l2-4a1 1 0 01.207-.293L14.586 2.586a2 2 0 012.828 0z"/>
        </svg>
    </a>
    @endif

    {{-- Delete Icon --}}
    @if($canDelete && $deleteRoute)
    <form action="{{ $deleteRoute }}" method="POST" onsubmit="return confirm('{{ $deleteConfirmMessage }}');" class="inline-block">
        @csrf
        @method('DELETE')
        <button type="submit" class="text-red-600 hover:text-red-800" title="Delete">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M6 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm4 0a1 1 0 112 0v6a1 1 0 11-2 0V8zm2-4a2 2 0 00-4 0H5a1 1 0 000 2h10a1 1 0 100-2h-3zM4 6h12v10a2 2 0 01-2 2H6a2 2 0 01-2-2V6z" clip-rule="evenodd"/>
            </svg>
        </button>
    </form>
    @endif
</div>
