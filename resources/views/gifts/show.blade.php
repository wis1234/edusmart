<x-app-layout>
    <div class="container mx-auto py-8 max-w-2xl">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold">Gifts Shop</h1>
            @if(auth()->check() && auth()->user()->hasRole('admin'))
            <a href="{{ route('gifts.create') }}" class="btn btn-success flex items-center gap-2">
                <i class="fas fa-gift"></i> Add Gift
            </a>
            @endif
        </div>
        <div class="bg-white rounded shadow p-6 flex flex-col items-center">
            <img src="{{ $gift->image_url }}" class="w-64 h-64 object-cover rounded mb-4" alt="{{ $gift->name }}">
            <h2 class="text-3xl font-bold mb-2">{{ $gift->name }}</h2>
            <span class="text-xl font-semibold text-indigo-600 mb-2">${{ $gift->value }}</span>
            <p class="text-gray-700 mb-4">{{ $gift->description }}</p>
            <div class="flex gap-2 mt-4">
                <a href="{{ route('gifts.index') }}" class="btn btn-secondary">Back to Gifts</a>
                @can('admin-gifts')
                <a href="{{ route('gifts.edit', $gift) }}" class="btn btn-warning">Edit</a>
                <form method="POST" action="{{ route('gifts.destroy', $gift) }}" onsubmit="return confirm('Are you sure?');" class="inline">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger" type="submit">Delete</button>
                </form>
                @endcan
            </div>
        </div>
    </div>
</x-app-layout> 