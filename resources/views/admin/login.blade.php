{{-- resources/views/admin/login.blade.php --}}

@extends('layouts.app')

@section('content')
<div class="flex items-center justify-center min-h-screen bg-gray-50 px-4">
    <div class="w-full max-w-md wire-card p-10 space-y-8">
        <h2 class="text-center text-3xl font-baloo text-iris dark:text-white mb-2">
            Admin Login
        </h2>

        <form method="POST" action="{{ route('admin.login.submit') }}" class="space-y-6">
            @csrf

            <x-input-field id="email" label="Email" type="email" name="email" required autofocus />
            <x-input-field id="password" label="Password" type="password" name="password" required />

            <div>
                <x-button type="primary" class="w-full mt-4">Login</x-button>
            </div>

            @if ($errors->any())
                <div class="text-red-500 text-sm mt-2">
                    {{ $errors->first() }}
                </div>
            @endif
        </form>
    </div>
</div>
@endsection

