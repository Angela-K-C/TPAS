@include('test.navbar')


@if(session('info'))
    <div style="color:blue; font-weight:bold;">
        {{ session('info') }}
    </div>
@endif

<h1>HomePage</h1>

@if(Auth::guard('web')->check())
    <p>Role: Admin</p>

    <p>Welcome, {{ Auth::guard('web')->user()->name }}!</p>

    <form action="{{ route('admin.logout') }}" method="POST" style="display:inline;">
        @csrf
        <button type="submit">Logout</button>
    </form>

@elseif (Auth::guard('university')->check())
    <p>Role: Student</p>

    <p>Welcome, {{ Auth::guard('university')->user()->name }}!</p>

    <form action="{{ route('student.logout') }}" method="POST" style="display:inline;">
        @csrf
        <button type="submit">Logout</button>
    </form>

@elseif(Auth::guard('guest')->check())
    <p>Role: Guest</p>

    <p>Welcome, {{ Auth::guard('guest')->user()->name }}!</p>

    <form action="{{ route('guest.logout') }}" method="POST" style="display:inline;">
        @csrf
        <button type="submit">Logout</button>
    </form>

@else

<a href="{{ route('test.login') }}">Go to Login</a>

@endif




