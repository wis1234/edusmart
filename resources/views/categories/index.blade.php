<x-app-layout>
    <div class="container mx-auto py-6">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-2xl font-bold">Categories</h1>
            @can('admin-gifts')
            <a href="{{ route('categories.create') }}" class="btn btn-primary">Add Category</a>
            @endcan
        </div>
        <table class="min-w-full bg-white rounded shadow mb-4">
            <thead>
                <tr>
                    <th class="p-2">Name</th>
                    @can('admin-gifts')
                    <th class="p-2">Actions</th>
                    @endcan
                </tr>
            </thead>
            <tbody>
                @foreach($categories as $category)
                    <tr>
                        <td class="p-2">{{ $category->name }}</td>
                        @can('admin-gifts')
                        <td class="p-2 flex gap-2">
                            <a href="{{ route('categories.edit', $category) }}" class="btn btn-sm btn-warning">Edit</a>
                            <form method="POST" action="{{ route('categories.destroy', $category) }}" onsubmit="return confirm('Are you sure?');">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger" type="submit">Delete</button>
                            </form>
                        </td>
                        @endcan
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="mt-6">
            {{ $categories->links() }}
        </div>
    </div>
</x-app-layout> 