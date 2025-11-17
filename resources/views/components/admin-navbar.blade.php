@props(['userLabel' => 'Admin'])

<nav class="bg-white border-b border-stroke shadow-sm sticky top-0 z-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="h-20 flex items-center justify-between">

            {{-- Left: Logo --}}
            <div class="flex items-center">
                <a href="{{ route('admin.dashboard') }}" class="text-3xl font-logo text-iris">
                    T.P.A.S
                </a>
            </div>

            {{-- Center: Navigation Links --}}
            <div class="hidden md:flex space-x-6 absolute left-1/2 transform -translate-x-1/2">
                <a href="{{ route('admin.dashboard') }}"
                   class="text-sm font-medium text-slate-600 hover:text-iris transition-colors">
                    Dashboard
                </a>
                <a href="{{ route('admin.applications.manage') }}"
                   class="text-sm font-medium text-slate-600 hover:text-iris transition-colors">
                    Review Applications
                </a>
                <a href="{{ route('admin.passes.expired') }}"
                   class="text-sm font-medium text-slate-600 hover:text-iris transition-colors">
                    Expired Passes
                </a>
            </div>

            {{-- Right: User info and Logout --}}
            <div class="flex items-center space-x-4">
                <span class="text-sm font-medium text-slate-500">
                    Hello, {{ $userLabel }}!
                </span>
                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button type="submit" class="text-sm font-semibold text-iris hover:text-red-500">
                        Logout
                    </button>
                </form>
            </div>

        </div>
    </div>
</nav>
