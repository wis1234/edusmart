<div class="mt-5">
    <h2 class="text-xl font-bold mb-2">Received Gifts</h2>
    @if($studentGifts->isEmpty())
        <p>No gifts received yet.</p>
    @else
        <ul class="space-y-2">
            @foreach($studentGifts as $gift)
                <li class="flex items-center gap-3 bg-gray-800 rounded p-2">
                    <img src="{{ $gift->product->image_url }}" width="50" class="rounded">
                    <div>
                        <div class="font-semibold">{{ $gift->product->name }} <span class="text-sm text-gray-400">(${{ $gift->product->price }})</span></div>
                        <div class="text-xs text-gray-300">Reason: {{ $gift->reason }}</div>
                        <div class="text-xs text-gray-400">Evaluation: {{ $gift->evaluation->subject->name }} ({{ $gift->evaluation->score }})</div>
                    </div>
                </li>
            @endforeach
        </ul>
    @endif
</div> 