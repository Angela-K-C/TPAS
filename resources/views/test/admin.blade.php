<!-- Success message -->
@if (session('success'))
    <div style="color: green; font-weight:bold;">
        {{ session('success') }}
    </div>
@endif

<!-- Error messages -->
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif


<form method="POST" action="{{ route('admin.login.submit') }}">
    @csrf
    <h2>Admin Login</h2>

    <input type="email" name="email" placeholder="Email" required><br>
    <input type="password" name="password" placeholder="Password" required><br>
    <button type="submit">Login</button>
</form>
