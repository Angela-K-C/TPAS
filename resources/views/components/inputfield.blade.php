{{-- resources/views/components/input-field.blade.php --}}
@props(['id', 'label', 'type' => 'text', 'name' => null])

<div {{ $attributes->merge(['class' => 'mt-4']) }}>
    <label for="{{ $id }}" class="block text-sm font-medium text-brand-text">
        {{ $label }}
    </label>
    <div class="mt-1">
        @if($type === 'select')
            {{--Select Dropdown --}}
            <select id="{{ $id }}" 
                    name="{{ $name ?? $id }}"
                    required
                    class="block w-full pl-3 pr-10 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-brand-primary focus:border-brand-primary sm:text-sm"
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
                       class="shadow-sm focus:ring-brand-primary focus:border-brand-primary block w-full sm:text-sm border border-gray-300 rounded-md"
                       {{ $attributes->except(['class', 'id', 'label', 'type', 'name']) }}
             ></textarea>
        @else
            {{-- Handle Standard Input --}}
            <input id="{{ $id }}"
                   name="{{ $name ?? $id }}"
                   type="{{ $type }}"
                   required
                   class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-brand-primary focus:border-brand-primary sm:text-sm"
                   {{ $attributes->except(['class', 'id', 'label', 'type', 'name']) }}
            >
        @endif
    </div>
</div>