<x-app-layout>
    <div class="container mx-auto py-6 max-w-lg">
        <h1 class="text-2xl font-bold mb-4">Edit Category</h1>
        <form method="POST" action="{{ route('categories.update', $category) }}">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label class="block font-semibold mb-1">Name</label>
                <input type="text" name="name" class="form-input w-full" required value="{{ old('name', $category->name) }}">
            </div>
            <div class="flex justify-end">
                <button class="btn btn-primary" type="submit">Update Category</button>
            </div>
        </form>
    </div>
</x-app-layout> 