{{-- resources/views/admin/login.blade.php --}}

@extends('layouts.app')

@section('content')
<div class="flex items-center justify-center min-h-screen bg-gray-50 px-4">
    <div class="w-full max-w-md wire-card p-10 space-y-8">
        <h2 class="text-center text-3xl font-baloo text-iris dark:text-white mb-2">
            Admin Login
        </h2>
        @if ($errors->any())
            <div class="rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-600">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('admin.login.submit') }}" class="space-y-6">
            @csrf
            <x-input-field id="email" label="Email" type="email" name="email" required autofocus value="{{ old('email') }}" />
            <x-input-field id="password" label="Password" type="password" name="password" required />
            <label for="remember" class="flex items-center gap-2 text-sm text-slate-500">
                <input type="checkbox" id="remember" name="remember" class="rounded border-gray-300 text-iris focus:ring-iris">
                Remember this device
            </label>
            <x-button type="primary" class="w-full mt-2">Login</x-button>
        </form>
        <p class="text-center text-sm">
            <a href="{{ route('login.choice') }}" class="font-medium text-iris hover:text-deep-slate">
                ← Back to Login Choice
            </a>
        </p>
    </div>
</div>
@endsection
