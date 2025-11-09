{{-- resources/views/components/status-badge.blade.php --}}
@props(['status'])

@php
    $normalizedStatus = strtolower($status);

    $classes = match ($normalizedStatus) {
        'approved', 'active' => 'bg-green-100 text-green-800',
        'pending' => 'bg-yellow-100 text-yellow-800',
        'rejected', 'inactive' => 'bg-red-100 text-red-800',
        default => 'bg-lilac-100 text-deep-slate',
    };

    $iconPath = match ($normalizedStatus) {
        'approved', 'active' => 'M5 13l4 4L19 7',   // check mark
        'pending' => 'M12 1a11 11 0 1 0 11 11A11 11 0 0 0 12 1zm0 5v6l4 2',             // clock
        'rejected', 'inactive' => 'M6 6l12 12M6 18L18 6', // cross
        default => null,
    };
@endphp

<span {{ $attributes->merge([
        'class' => $classes.' inline-flex items-center gap-1 px-3 py-1 rounded-full text-sm font-medium shadow-sm'
    ]) }}>
    @if($iconPath)
        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="{{ $iconPath }}" />
        </svg>
    @endif
    {{ $status }}
</span>
