<nav>
    @if (Auth::guard('web')->check() || Auth::guard('guest')->check() || Auth::guard('university')->check() )
        <a href="{{ route('test.home') }}">Home</a>
        <a href="{{ route('passes.index') }}">Passes</a>
    @endif
</nav>