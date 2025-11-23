@props(['userLabel' => 'Security', 'logoutRoute' => route('security.logout')])

<nav class="bg-white border-b border-stroke shadow-sm sticky top-0 z-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="h-20 flex items-center justify-between">
            <div class="flex items-center">
                <a href="{{ route('security.verify') }}" class="flex items-center">
                    <img src="{{ asset('logo.png') }}" alt="TPAS Logo" class="h-14 w-auto object-contain">
                </a>
            </div>

            <div class="hidden md:flex space-x-6 absolute left-1/2 transform -translate-x-1/2">
                <a href="{{ route('security.verify') }}"
                   class="text-sm font-medium text-slate-600 hover:text-iris transition-colors">
                    Verify Pass
                </a>
                <a href="{{ route('help') }}"
                   class="text-sm font-medium text-slate-600 hover:text-iris transition-colors">
                    Help
                </a>
            </div>

            <div class="flex items-center space-x-4">
                <span class="text-sm font-medium text-slate-500">
                    {{ $userLabel }}
                </span>
                <form method="POST" action="{{ $logoutRoute }}">
                    @csrf
                    <button type="submit" class="text-sm font-semibold text-iris hover:text-red-500">
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>
