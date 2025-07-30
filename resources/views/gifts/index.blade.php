<x-app-layout>
    <div class="container mx-auto py-6">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-2xl font-bold">Available Gifts</h1>
            <div class="flex gap-2">
                <a href="{{ route('ecommerce.index') }}" class="btn btn-secondary">E-commerce Shop</a>
                @can('admin-gifts')
                <a href="{{ route('gifts.admin') }}" class="btn btn-primary">Admin Dashboard</a>
                <a href="{{ route('gifts.create') }}" class="btn btn-success">Add Gift</a>
                @endcan
            </div>
        </div>
        @if($gifts->isEmpty())
            <div class="text-center py-12">
                <p class="text-gray-500 mb-4">No gifts available yet.</p>
                @can('admin-gifts')
                <a href="{{ route('gifts.create') }}" class="btn btn-success">Add the first Gift</a>
                @endcan
            </div>
        @else
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($gifts as $gift)
                <div class="bg-white rounded shadow p-4 flex flex-col">
                    <img src="{{ $gift->image_url }}" class="w-full h-40 object-cover rounded mb-2" alt="{{ $gift->name }}">
                    <div class="flex-1">
                        <h5 class="font-semibold text-lg">{{ $gift->name }}</h5>
                        <p class="text-sm mb-2">{{ $gift->description }}</p>
                    </div>
                    <span class="font-bold mb-2">${{ $gift->value }}</span>
                    <div class="flex gap-2 mt-2">
                        <a href="{{ route('gifts.show', $gift) }}" class="btn btn-info btn-sm">Details</a>
                        @can('admin-gifts')
                        <a href="{{ route('gifts.edit', $gift) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form method="POST" action="{{ route('gifts.destroy', $gift) }}" onsubmit="return confirm('Are you sure?');">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm" type="submit">Delete</button>
                        </form>
                        @endcan
                    </div>
                </div>
            @endforeach
        </div>
        <div class="mt-6">
            {{ $gifts->links() }}
        </div>
        @endif
    </div>
</x-app-layout> 