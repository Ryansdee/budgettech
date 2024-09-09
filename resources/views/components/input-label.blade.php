@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-medium bg-dark text-sm text-gray-700']) }}>
    {{ $value ?? $slot }}
</label>
