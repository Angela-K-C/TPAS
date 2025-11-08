{{-- resources/views/components/navbar.blade.php --}}
@props(['userLabel' => 'Student'])

<nav class="bg-white border-b border-stroke sticky top-0 z-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="h-20 flex items-center justify-between">
            
            {{-- Logo and page hints --}}
            <div class="flex items-center space-x-6">
                <a href="{{ route('dashboard') }}" class="text-3xl font-hand text-slate-900 tracking-tight">
                    Logo
                </a>
            </div>
            
            {{-- User actions --}}
            <div class="flex items-center space-x-3">
                <span class="text-sm font-medium text-slate-500 hidden md:block">
                    Hello, {{ $userLabel }}!
                </span>
                
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-sm font-semibold text-slate-500 hover:text-red-500">
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>
