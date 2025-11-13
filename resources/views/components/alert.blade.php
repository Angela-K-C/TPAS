{{-- resources/views/components/alert.blade.php --}}
@props(['type' => 'info'])

@php
    [$border, $background, $text, $icon] = match ($type) {
        'success' => ['border-mint/60', 'bg-mint/15 dark:bg-mint/20', 'text-deep-slate dark:text-slate-100', 'M5 13l4 4L19 7'],
        'warning' => ['border-amber/60', 'bg-amber/15 dark:bg-amber/20', 'text-deep-slate dark:text-slate-100', 'M12 8v4l2.5 2.5'],
        'danger' => ['border-red-400/70', 'bg-red-500/10 dark:bg-red-500/20', 'text-red-700 dark:text-red-200', 'M6 6l12 12M6 18l12-12'],
        default => ['border-lilac/70', 'bg-lilac/20 dark:bg-slate-700/40', 'text-deep-slate dark:text-slate-100', null],
    };
@endphp

<div {{ $attributes->merge(['class' => "rounded-xl border {$border} {$background} {$text} px-5 py-4 flex items-start gap-3"]) }}>
    @if($icon)
        <svg class="w-4 h-4 mt-0.5 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
            stroke-linecap="round" stroke-linejoin="round">
            <path d="{{ $icon }}" />
        </svg>
    @endif
    <div class="text-sm leading-relaxed">
        {{ $slot }}
    </div>
</div>
