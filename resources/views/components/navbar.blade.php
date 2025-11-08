{{-- resources/views/components/navbar.blade.php --}}
@props([]) {{-- Define props, even if empty, for component compatibility --}}

<nav class="bg-white shadow-sm sticky top-0 z-10 border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            
            {{-- Logo and Home Link --}}
            <div class="flex items-center">
                <a href="{{ route('dashboard') }}" class="text-2xl font-extrabold text-brand-primary tracking-tight">
                    TPAS
                </a>
            </div>
            
            {{-- User Actions / Profile --}}
            <div class="flex items-center space-x-4">
                <span class="text-sm font-medium text-brand-text hidden sm:block">
                    Hello, Student!
                </span>
                
                {{-- Logout Button/Link --}}
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" 
                            class="text-sm font-medium text-brand-muted hover:text-brand-danger transition duration-150 ease-in-out py-2 px-3 rounded-md hover:bg-brand-bg"
                    >
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>