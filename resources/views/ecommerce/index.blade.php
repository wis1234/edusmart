<x-app-layout>
    <div class="container mx-auto py-6">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-2xl font-bold">Gifts Shop</h1>
            <div class="flex gap-2">
                <a href="{{ route('cart.index') }}" class="btn btn-secondary flex items-center gap-2">
                    <i class="fas fa-shopping-cart"></i> Cart
                </a>
                @if(auth()->check() && auth()->user()->isAdmin())
                <a href="{{ route('gifts.create') }}" class="btn btn-primary flex items-center gap-2">
                    <i class="fas fa-gift"></i> Create Gift
                </a>
                @endif
            </div>
        </div>
        <form method="GET" action="{{ route('gifts.index') }}" class="mb-6 flex flex-wrap gap-2">
            <input type="text" name="search" placeholder="Search for a gift..." value="{{ request('search') }}" class="border rounded px-2 py-1">
            <select name="category" class="border rounded px-2 py-1">
                <option value="">All categories</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-primary">Filter</button>
        </form>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($products as $product)
                <div class="bg-gray-800 text-white rounded shadow p-4 flex flex-col">
                    <img src="{{ $product->image_url }}" class="w-full h-40 object-cover rounded mb-2" alt="{{ $product->name }}">
                    <div class="flex-1">
                        <h5 class="font-semibold text-lg">{{ $product->name }}</h5>
                        <p class="text-sm mb-2">{{ $product->description }}</p>
                    </div>
                    <span class="font-bold mb-2">${{ $product->price }}</span>
                    <div class="flex gap-2 mt-2">
                        <a href="{{ route('gifts.show', $product) }}" class="btn btn-info btn-sm">Details</a>
                        <form method="POST" action="{{ route('cart.add', $product) }}">
                            @csrf
                            <button class="btn btn-success btn-sm" type="submit">Add to cart</button>
                        </form>
                        @if(auth()->check() && auth()->user()->isAdmin())
                        <a href="{{ route('gifts.edit', $product) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form method="POST" action="{{ route('gifts.destroy', $product) }}" onsubmit="return confirm('Are you sure?');">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm" type="submit">Delete</button>
                        </form>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
        <div class="mt-6">
            {{ $products->links() }}
        </div>
    </div>
</x-app-layout> 