{{-- resources/views/components/navbar.blade.php --}}
@props(['userLabel' => 'Student'])

<nav class="bg-white border-b border-stroke sticky top-0 z-20 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="h-20 flex items-center justify-between">

            {{-- Left: Logo --}}
            <div class="flex items-center">
                <a href="{{ route('dashboard') }}" class="text-3xl font-logo text-iris">
                    T.P.A.S
                </a>
            </div>

            {{-- Center: Navigation links --}}
            <div class="hidden md:flex space-x-6 mx-auto">
                <a href="{{ route('dashboard') }}"
                    class="text-sm font-medium text-slate-600 hover:text-iris transition-colors">
                    Dashboard
                </a>
                <a href="{{ route('profile') }}"
                    class="text-sm font-medium text-slate-600 hover:text-iris transition-colors">
                    Profile
                </a>
            </div>

            {{-- Right: User actions --}}
            <div class="flex items-center space-x-4">
                <span class="text-sm font-medium text-slate-500">
                    Hello, {{ $userLabel }}!
                </span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-sm font-semibold text-iris hover:text-red-500">
                        Logout
                    </button>
                </form>
            </div>

        </div>
    </div>
</nav>
