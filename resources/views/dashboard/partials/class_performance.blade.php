@foreach($classes as $class)
    <h3 class="text-lg font-bold mt-6 mb-2">{{ $class->name }}</h3>
    <table class="min-w-full bg-gray-800 text-white rounded mb-4">
        <thead>
            <tr>
                <th class="p-2">Student</th>
                <th class="p-2">Subject</th>
                <th class="p-2">Average</th>
                <th class="p-2">Received Gifts</th>
            </tr>
        </thead>
        <tbody>
            @foreach($class->students as $student)
                @foreach($student->subjects as $subject)
                    <tr>
                        <td class="p-2">{{ $student->full_name }}</td>
                        <td class="p-2">{{ $subject->name }}</td>
                        <td class="p-2">{{ $student->averageForSubject($subject->id) }}</td>
                        <td class="p-2">
                            @foreach($student->giftsForSubject($subject->id) as $gift)
                                <img src="{{ $gift->product->image_url }}" width="30" class="inline-block rounded" title="{{ $gift->product->name }}">
                            @endforeach
                        </td>
                    </tr>
                @endforeach
            @endforeach
        </tbody>
    </table>
@endforeach 