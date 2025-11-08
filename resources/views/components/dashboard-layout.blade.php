{{-- resources/views/components/dashboard-layout.blade.php --}}

@props(['title' => 'Dashboard', 'user' => 'Student'])

@extends('layouts.app')

@section('content')
<div class="min-h-screen flex flex-col">
    
    {{-- 1. Navigation Bar: Now called as a Component --}}
    <x-navbar :user-label="$user" />
    
    {{-- 2. Page Header --}}
    <header class="bg-white border-b border-stroke">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <p class="text-sm uppercase tracking-[0.3em] text-warm-gray mb-2">Temporary Pass System</p>
            <h1 class="text-4xl font-hand font-semibold text-slate-900">
                {{ $title }}
            </h1>
        </div>
    </header>

    {{-- 3. Main Content Wrapper --}}
    <main class="flex-grow bg-gray-50">
        <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
            <div class="px-4 py-6 sm:px-0">
                {{ $slot }}
            </div>
        </div>
    </main>
</div>
@endsection
