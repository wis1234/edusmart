<x-app-layout>
    <div class="container mx-auto py-6 max-w-lg">
        <h1 class="text-2xl font-bold mb-4">Edit Gift</h1>
        <form method="POST" action="{{ route('gifts.update', $gift) }}">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label class="block font-semibold mb-1">Name</label>
                <input type="text" name="name" class="form-input w-full" required value="{{ old('name', $gift->name) }}">
            </div>
            <div class="mb-4">
                <label class="block font-semibold mb-1">Description</label>
                <textarea name="description" class="form-input w-full">{{ old('description', $gift->description) }}</textarea>
            </div>
            <div class="mb-4">
                <label class="block font-semibold mb-1">Image URL</label>
                <input type="url" name="image_url" class="form-input w-full" value="{{ old('image_url', $gift->image_url) }}">
            </div>
            <div class="mb-4">
                <label class="block font-semibold mb-1">Value ($)</label>
                <input type="number" name="value" class="form-input w-full" min="0" step="0.01" required value="{{ old('value', $gift->value) }}">
            </div>
            <div class="flex justify-end">
                <button class="btn btn-primary" type="submit">Update Gift</button>
            </div>
        </form>
    </div>
</x-app-layout> 