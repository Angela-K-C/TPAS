{{-- resources/views/components/card.blade.php --}}
@props(['header' => null])

<div {{ $attributes->merge(['class' => 'wire-card overflow-hidden']) }}>
    
    {{-- Header Section --}}
    @if ($header)
        <div class="px-6 py-4 border-b border-stroke bg-slate-50">
            <h3 class="text-lg leading-6 font-semibold text-slate-700 font-hand">
                {{ $header }}
            </h3>
        </div>
    @endif
    
    {{-- Content Slot --}}
    <div class="p-6 space-y-4">
        {{ $slot }}
    </div>
</div>
