{{-- resources/views/auth/student-login.blade.php --}}

@extends('layouts.app')

@section('content')
<div class="flex items-center justify-center min-h-screen bg-gray-50 px-4">
    <div class="w-full max-w-md wire-card p-10 space-y-8">
        <h2 class="text-center text-3xl font-hand text-slate-900 dark:text-white">
            Student Login
        </h2>
        <form class="space-y-6" action="{{ route('student.login.submit') }}" method="POST">
            @csrf

            @if ($errors->any())
                <div class="rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-600">
                    {{ $errors->first() }}
                </div>
            @endif

            {{-- University Email --}}
            <x-input-field id="email" label="University Email" type="email" name="email" value="{{ old('email') }}" required autofocus />

            {{-- Password --}}
            <x-input-field id="password" label="Password" type="password" name="password" required />

            <div>
                <x-button type="primary" class="w-full mt-4">
                    Login
                </x-button>
            </div>
        </form>
        
        <p class="mt-4 text-center text-sm">
            <a href="{{ route('login.choice') }}" class="font-medium text-iris hover:text-deep-slate">
                <- Back to Login Choice
            </a>
        </p>
    </div>
</div>
@endsection
