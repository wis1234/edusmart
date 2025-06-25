@props([
    'type' => 'text',
    'name',
    'label',
    'icon' => null,
    'value' => '',
    'required' => false,
    'autocomplete' => null,
    'placeholder' => null,
])
<div class="mb-4">
    <label for="{{ $name }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ $label }}</label>
    <div class="mt-1 relative">
        <input
            id="{{ $name }}"
            name="{{ $name }}"
            type="{{ $type }}"
            @if($autocomplete) autocomplete="{{ $autocomplete }}" @endif
            @if($required) required @endif
            value="{{ old($name, $value) }}"
            placeholder="{{ $placeholder ?? $label }}"
            {{ $attributes->merge(['class' => 'block w-full px-4 py-3 border border-gray-300 dark:border-gray-700 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-900 dark:text-white']) }}
        >
        @if($icon)
            <span class="absolute inset-y-0 right-3 flex items-center pointer-events-none">
                <i class="{{ $icon }} text-gray-400"></i>
            </span>
        @endif
    </div>
    @error($name)
        <span class="text-red-600 text-xs">{{ $message }}</span>
    @enderror
</div> 