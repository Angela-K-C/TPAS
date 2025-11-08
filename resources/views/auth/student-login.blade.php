{{-- resources/views/auth/student-login.blade.php --}}

@extends('layouts.app')

@section('content')
<div class="flex items-center justify-center min-h-screen bg-brand-bg">
    <div class="w-full max-w-sm p-8 space-y-6 bg-white shadow-lg rounded-lg">
        <h2 class="text-center text-2xl font-bold text-brand-text">
            Student Login
        </h2>
        <form class="space-y-6" action="{{ route('student.login') }}" method="POST">
            @csrf
            
            {{-- Admission Number --}}
            <x-input-field id="admission_number" label="Admission Number" type="text" name="admission_number" />

            {{-- Password --}}
            <x-input-field id="password" label="Password" type="password" name="password" />

            <div>
                <x-button type="primary" class="w-full mt-4">
                    Login
                </x-button>
            </div>
        </form>
        
        <p class="mt-4 text-center text-sm">
            <a href="{{ route('login.choice') }}" class="font-medium text-brand-primary hover:text-indigo-700">
                ← Back to Login Choice
            </a>
        </p>
    </div>
</div>
@endsection