<x-app-layout>
    <div class="container mx-auto py-6">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-2xl font-bold">Manage Gifts</h1>
            <a href="{{ route('gifts.create') }}" class="btn btn-primary">Add Gift</a>
        </div>
        <table class="min-w-full bg-white rounded shadow mb-4">
            <thead>
                <tr>
                    <th class="p-2">Image</th>
                    <th class="p-2">Name</th>
                    <th class="p-2">Value</th>
                    <th class="p-2">Description</th>
                    <th class="p-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($gifts as $gift)
                    <tr>
                        <td class="p-2"><img src="{{ $gift->image_url }}" class="w-16 h-16 object-cover rounded" alt="{{ $gift->name }}"></td>
                        <td class="p-2">{{ $gift->name }}</td>
                        <td class="p-2">${{ $gift->value }}</td>
                        <td class="p-2">{{ $gift->description }}</td>
                        <td class="p-2 flex gap-2">
                            <a href="{{ route('gifts.edit', $gift) }}" class="btn btn-sm btn-warning">Edit</a>
                            <form method="POST" action="{{ route('gifts.destroy', $gift) }}" onsubmit="return confirm('Are you sure?');">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger" type="submit">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="mt-6">
            {{ $gifts->links() }}
        </div>
    </div>
</x-app-layout> 