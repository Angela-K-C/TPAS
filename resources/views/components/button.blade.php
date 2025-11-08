{{-- resources/views/components/button.blade.php --}}

@props(['type' => 'primary', 'href' => null])

@php
    $typeClasses = match ($type) {
        'primary' => 'wire-button-primary',
        'danger' => 'wire-button-danger',
        'secondary' => 'wire-button-secondary',
        default => 'wire-button wire-button-secondary',
    };

    $classes = trim($typeClasses . ' ' . ($attributes->get('class') ?? ''));
@endphp

{{-- determine if its a link or a button --}}

@if ($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </a>
@else
    <button {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </button>
@endif
