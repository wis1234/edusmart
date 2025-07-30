<x-app-layout>
    <div class="container mx-auto py-6">
        <h1 class="text-2xl font-bold mb-4">My Orders</h1>
        @if($orders->isEmpty())
            <p>No orders placed yet.</p>
        @else
            <table class="min-w-full bg-gray-800 text-white rounded mb-4">
                <thead>
                    <tr>
                        <th class="p-2">Date</th>
                        <th class="p-2">Gifts</th>
                        <th class="p-2">Amount</th>
                        <th class="p-2">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                        <tr>
                            <td class="p-2">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                            <td class="p-2">
                                @foreach($order->items as $item)
                                    <div class="flex items-center gap-2 mb-1">
                                        <img src="{{ $item->product->image_url }}" class="w-8 h-8 object-cover rounded" alt="{{ $item->product->name }}">
                                        {{ $item->product->name }} x{{ $item->quantity }}
                                    </div>
                                @endforeach
                            </td>
                            <td class="p-2">${{ $order->total }}</td>
                            <td class="p-2">
                                @if($order->status === 'pending')
                                    <span class="text-yellow-400">Pending</span>
                                @elseif($order->status === 'validated')
                                    <span class="text-green-400">Validated</span>
                                @elseif($order->status === 'rejected')
                                    <span class="text-red-400">Rejected</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $orders->links() }}
        @endif
    </div>
</x-app-layout> 