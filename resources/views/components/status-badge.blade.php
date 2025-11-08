{{-- resources/views/components/status-badge.blade.php --}}
@props(['status'])

@php
    $normalizedStatus = strtolower($status);

    $classes = match ($normalizedStatus) {
        'approved', 'active' => 'wire-status-pill-active',
        'pending' => 'wire-status-pill-pending',
        'rejected', 'inactive' => 'wire-status-pill-inactive',
        default => 'wire-status-pill bg-lilac text-deep-slate',
    };

    $iconPath = match ($normalizedStatus) {
        'approved', 'active' => 'M5 13l4 4L19 7',
        'pending' => 'M12 8v4l2.5 2.5',
        'rejected', 'inactive' => 'M6 6l12 12M6 18L18 6',
        default => null,
    };
@endphp

<span {{ $attributes->merge(['class' => $classes.' inline-flex items-center gap-1']) }}>
    @if($iconPath)
        <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="{{ $iconPath }}" />
        </svg>
    @endif
    {{ $status }}
</span>
