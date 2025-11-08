{{-- resources/views/components/button.blade.php --}}

@props(['type' => 'primary', 'href' => null])

@php
    $base_classes = 'inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm transition duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-offset-2';

    $type_classes = match ($type) {
        'primary' => 'text-white bg-brand-primary however:bg-indigo-700 focus:ring-brand-danger',

        'danger' => 'text-white bg-brand-primary hover:bg-red-700 focus:ring-brand-danger',
    
        'secondary' => 'text-brand-text bg-white border-gray-300 hover:bg-gray-50 focus:ring-brand-primary',
        
        default => 'text-gray-700 bg-gray-100 hover:bg-gray-200 focus:ring-gray-500',
    
    };

    $classes = $base_classes . ' ' . $type_classes . ' ' . ($attributes->get('class', '') ?? '');
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