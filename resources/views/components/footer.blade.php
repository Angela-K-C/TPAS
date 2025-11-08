{{-- resources/views/components/footer.blade.php --}}
@props([]) {{-- Define props, even if empty, for component compatibility --}}

<footer class="mt-12 border-t border-gray-200 bg-white">
    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <div class="md:flex md:items-center md:justify-between">
            
            <p class="text-center text-sm text-brand-muted md:order-1">
                &copy; {{ date('Y') }} Temporary Pass Application System (TPAS). All rights reserved.
            </p>
            
            <div class="flex justify-center space-x-6 mt-4 md:mt-0 md:order-2">
                <a href="#" class="text-sm text-brand-muted hover:text-brand-primary">Privacy Policy</a>
                <a href="#" class="text-sm text-brand-muted hover:text-brand-primary">Terms of Service</a>
            </div>
            
        </div>
    </div>
</footer>