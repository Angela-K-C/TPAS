{{-- resources/views/components/footer.blade.php --}}
@props([]) {{-- Define props, even if empty, for component compatibility --}}

<footer class="mt-16 border-t border-stroke bg-white">
    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <div class="md:flex md:items-center md:justify-between">
            
            <p class="text-center text-sm text-slate-500 md:order-1">
                &copy; {{ date('Y') }} Temporary Pass Application System (TPAS). All rights reserved.
            </p>
            
            <div class="flex justify-center space-x-6 mt-4 md:mt-0 md:order-2">
                <a href="{{ route('help') }}" class="text-sm text-slate-500 hover:text-slate-700">Help &amp; FAQ</a>
                <a href="#" class="text-sm text-slate-500 hover:text-slate-700">Privacy Policy</a>
                <a href="#" class="text-sm text-slate-500 hover:text-slate-700">Terms of Service</a>
            </div>
            
        </div>
    </div>
</footer>
