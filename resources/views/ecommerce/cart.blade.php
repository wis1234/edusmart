<x-app-layout>
    <div class="container mx-auto py-6">
        <h1 class="text-2xl font-bold mb-4">My Cart</h1>
        @if(count($cartItems) === 0)
            <p>Your cart is empty.</p>
        @else
            <table class="min-w-full bg-gray-800 text-white rounded mb-4">
                <thead>
                    <tr>
                        <th class="p-2">Product</th>
                        <th class="p-2">Price</th>
                        <th class="p-2">Quantity</th>
                        <th class="p-2">Total</th>
                        <th class="p-2">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($cartItems as $item)
                        <tr>
                            <td class="p-2 flex items-center gap-2">
                                <img src="{{ $item->product->image_url }}" class="w-12 h-12 object-cover rounded" alt="{{ $item->product->name }}">
                                {{ $item->product->name }}
                            </td>
                            <td class="p-2">${{ $item->product->price }}</td>
                            <td class="p-2">{{ $item->quantity }}</td>
                            <td class="p-2">${{ $item->product->price * $item->quantity }}</td>
                            <td class="p-2">
                                <form method="POST" action="{{ route('cart.remove', $item->product) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger">Remove</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="flex justify-between items-center mb-4">
                <span class="font-bold text-lg">Total: ${{ $total }}</span>
                <a href="{{ route('checkout.index') }}" class="btn btn-primary">Checkout</a>
            </div>
        @endif
    </div>
</x-app-layout> 