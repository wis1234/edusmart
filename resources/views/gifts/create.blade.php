<x-app-layout>
    <div class="container mx-auto py-6 max-w-lg">
        @if(!auth()->check() || !auth()->user()->isAdmin())
            <div class="bg-red-100 text-red-800 p-4 rounded mb-4">Access Denied. You do not have permission to access this page.</div>
        @else
        <h1 class="text-2xl font-bold mb-4">Add New Gift</h1>
        <form method="POST" action="{{ route('gifts.store') }}">
            @csrf
            <div class="mb-4">
                <label class="block font-semibold mb-1">Name</label>
                <input type="text" name="name" class="form-input w-full" required value="{{ old('name') }}">
            </div>
            <div class="mb-4">
                <label class="block font-semibold mb-1">Description</label>
                <textarea name="description" class="form-input w-full">{{ old('description') }}</textarea>
            </div>
            <div class="mb-4">
                <label class="block font-semibold mb-1">Image URL</label>
                <input type="url" name="image_url" class="form-input w-full" value="{{ old('image_url') }}">
            </div>
            <div class="mb-4">
                <label class="block font-semibold mb-1">Value ($)</label>
                <input type="number" name="value" class="form-input w-full" min="0" step="0.01" required value="{{ old('value') }}">
            </div>
            <div class="flex justify-end">
                <button class="btn btn-primary" type="submit">Create Gift</button>
            </div>
        </form>
        @endif
    </div>
</x-app-layout> 