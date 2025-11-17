{{-- resources/views/auth/login-choice.blade.php --}}

@extends('layouts.app')

@section('content')
<div class="flex items-center justify-center min-h-screen bg-gray-50 px-4">
    <div class="w-full max-w-md wire-card p-10 space-y-8 text-center">
        <h2 class="text-4xl font-hand text-slate-900 dark:text-white">
            TPAS
        </h2>
        <p class="text-base text-slate-500 dark:text-slate-300">
            Choose how you would like to sign in.
        </p>
        <div class="space-y-4">
            {{-- Student Login --}}
            <x-button type="primary" href="{{ route('student.login') }}" class="w-full">
                Login as a Student
            </x-button>

            {{-- Guest Login --}}
            <x-button type="secondary" href="{{ route('guest.login') }}" class="w-full">
                Login as a Guest
            </x-button>

            {{-- Admin Login --}}
            <x-button type="tertiary" href="{{ route('admin.login') }}" class="w-full">
                Login as an Admin
            </x-button>
            
        </div>
    </div>
</div>
@endsection
