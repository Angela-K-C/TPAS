{{-- resources/views/components/input-field.blade.php --}}
@props(['id', 'label', 'type' => 'text', 'name' => null, 'helper' => null])

<div {{ $attributes->merge(['class' => 'space-y-2']) }}>
    <label for="{{ $id }}" class="block text-base font-semibold text-slate-700 font-hand">
        {{ $label }}
    </label>
    <div class="mt-1">
        @if($type === 'select')
            {{--Select Dropdown --}}
            <select id="{{ $id }}" 
                    name="{{ $name ?? $id }}"
                    required
                    class="wire-input cursor-pointer"
                    {{ $attributes->except(['class', 'id', 'label', 'type', 'name']) }}
            >
                {{ $slot }} {{-- Options are passed in --}}
            </select>
        @elseif($type === 'textarea')
             {{-- Handle Textarea --}}
             <textarea id="{{ $id }}"
                       name="{{ $name ?? $id }}"
                       rows="3"
                       required
                       class="wire-input"
                       {{ $attributes->except(['class', 'id', 'label', 'type', 'name']) }}
             ></textarea>
        @else
            {{-- Handle Standard Input --}}
            <input id="{{ $id }}"
                   name="{{ $name ?? $id }}"
                   type="{{ $type }}"
                   required
                   class="wire-input"
                   {{ $attributes->except(['class', 'id', 'label', 'type', 'name']) }}
            >
        @endif
    </div>
    @if($helper)
        <p class="text-xs text-warm-gray dark:text-slate-400">{{ $helper }}</p>
    @endif
</div>
