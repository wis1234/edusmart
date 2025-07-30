<x-app-layout>
    <div class="container mx-auto py-6">
        <div class="flex flex-col md:flex-row gap-6">
            <div class="md:w-1/2">
                <img src="{{ $product->image_url }}" class="w-full h-80 object-cover rounded shadow" alt="{{ $product->name }}">
            </div>
            <div class="md:w-1/2 flex flex-col justify-between">
                <div>
                    <h1 class="text-2xl font-bold mb-2">{{ $product->name }}</h1>
                    <p class="mb-4">{{ $product->description }}</p>
                    <span class="font-bold text-lg mb-4 block">${{ $product->price }}</span>
                </div>
                <form method="POST" action="{{ route('cart.add', $product) }}">
                    @csrf
                    <button class="btn btn-success w-full" type="submit">Add to cart</button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout> 