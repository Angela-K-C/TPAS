{{-- resources/views/components/status-badge.blade.php --}}
@props(['status'])

@php
    $normalizedStatus = strtolower($status);
    
 
    $classes = match ($normalizedStatus) {
        'approved', 'active' => 'bg-green-100 text-green-800',
        'pending' => 'bg-yellow-100 text-yellow-800',
        'rejected', 'inactive' => 'bg-red-100 text-brand-danger', // Using centralized danger color
        default => 'bg-gray-100 text-gray-800', 
    };
    
    // Apply common badge styles
    $base_classes = 'px-2 inline-flex text-xs leading-5 font-semibold rounded-full';
    $classes = $base_classes . ' ' . $classes;
@endphp

<span {{ $attributes->merge(['class' => $classes]) }}>
    {{ $status }}
</span>