{{-- resources/views/auth/login-choice.blade.php --}}

@extends('layouts.app')

@section('content')
<div class="flex items-center justify-center min-h-screen bg-brand-bg">
    <div class="w-full max-w-sm p-8 space-y-8 bg-white shadow-lg rounded-lg">
        <h2 class="text-center text-3xl font-extrabold text-brand-primary">
            TPAS
        </h2>
        <p class="text-center text-sm font-medium text-brand-text">
            Please select your user type to proceed.
        </p>
        <div class="space-y-4">
            {{-- Student Login --}}
            <x-button type="primary" href="{{ route('student.login') }}" class="w-full">
                Login as a Student
            </x-button>

            {{-- Guest Login --}}
            <x-button type="secondary" href="{{ route('guest.login') }}" class="w-full">
                Login as a Guest/Visitor
            </x-button>
        </div>
    </div>
</div>
@endsection