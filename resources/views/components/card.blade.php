{{-- resources/views/components/card.blade.php --}}
@props(['header' => null])

<div {{ $attributes->merge(['class' => 'bg-white shadow overflow-hidden sm:rounded-lg']) }}>
    
    {{-- Header Section --}}
    @if ($header)
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
            <h3 class="text-lg leading-6 font-medium text-brand-text">
                {{ $header }}
            </h3>
        </div>
    @endif
    
    {{-- Content Slot --}}
    <div class="p-6">
        {{ $slot }}
    </div>
</div>