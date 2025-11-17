{{-- resources/views/auth/guest-login.blade.php --}}

@extends('layouts.app')

@section('content')
<div class="flex items-center justify-center min-h-screen bg-gray-50 px-4">
    <div class="w-full max-w-md wire-card p-10 space-y-8">
        <h2 class="text-center text-3xl font-hand text-slate-900 dark:text-white">
            Guest Login
        </h2>
        <form class="space-y-6" action="{{ route('guest.login.submit') }}" method="POST">
            @csrf
            
            {{-- Email --}}
            <x-input-field id="email" label="Email" type="email" name="email" />

            {{-- Password --}}
            <x-input-field id="password" label="Password" type="password" name="password" />

            <div class="space-y-3 pt-2">
                <x-button type="primary" class="w-full">
                    Login
                </x-button>
                
                <x-button type="secondary" class="w-full">
                    Continue with Google
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
