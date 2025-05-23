<div class="assignment-entry grid grid-cols-4 gap-4 mb-4">
    <div>
        <label class="block font-semibold mb-1">Subject*</label>
        <select name="subjects[]" class="w-full border border-gray-300 rounded px-3 py-2" required>
            <option value="">Select Subject</option>
            @foreach($subjects as $s)
                @if($s->is_active)
                    <option value="{{ $s->id }}" {{ ($subjectId ?? '') == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
                @endif
            @endforeach
        </select>
    </div>

    <div>
        <label class="block font-semibold mb-1">Class Room*</label>
        <select name="class_rooms[]" class="w-full border border-gray-300 rounded px-3 py-2" required>
            <option value="">Select Class Room</option>
            @foreach($classRooms as $cr)
                <option value="{{ $cr->id }}" {{ ($classRoomId ?? '') == $cr->id ? 'selected' : '' }}>{{ $cr->name }} ({{ $cr->grade_level }})</option>
            @endforeach
        </select>
    </div>

    <div>
        <label class="block font-semibold mb-1">School*</label>
        <select name="schools[]" class="w-full border border-gray-300 rounded px-3 py-2" required>
            <option value="">Select School</option>
            @foreach($schools as $school)
                <option value="{{ $school->id }}" {{ ($schoolId ?? '') == $school->id ? 'selected' : '' }}>{{ $school->name }}</option>
            @endforeach
        </select>
    </div>

    <div>
        <label class="block font-semibold mb-1">Year*</label>
        <input type="number" name="years[]" class="w-full border border-gray-300 rounded px-3 py-2" value="{{ $year ?? date('Y') }}" min="2000" max="2100" required>
        @if (!isset($loop) || !$loop->first)
            <button type="button" class="mt-2 bg-red-500 hover:bg-red-700 text-white text-sm py-1 px-2 rounded remove-assignment">Remove</button>
        @endif
    </div>
</div>
