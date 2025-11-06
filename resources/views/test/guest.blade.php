<h2>Guest Login</h2>

@if ($errors->any())
    <div style="color:red;">
        @foreach ($errors->all() as $error)
            <p>{{ $error }}</p>
        @endforeach
    </div>
@endif

<form method="POST" action="{{ route('guest.login.submit') }}">
    @csrf
    <input type="email" name="email" placeholder="Email" required><br><br>
    <button type="submit">Login</button>
</form>
