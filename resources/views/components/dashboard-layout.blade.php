{{-- resources/views/components/dashboard-layout.blade.php --}}

@props(['title' => 'Dashboard', 'user' => 'Student'])

@extends('layouts.app')

@section('content')
<div class="min-h-screen flex flex-col bg-gray-50">
    
    {{-- 1. Navigation Bar --}}
    <x-navbar :user-label="$user" />

    {{-- 2. Page Header --}}
    <header class="bg-white shadow-sm border-b border-stroke">
        <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8 flex items-center justify-between">
            <p class="text-lg font-medium text-slate-900">{{ $title }}</p>
            <p class="text-sm uppercase tracking-widest text-warm-gray">Temporary Pass System</p>
        </div>
    </header>

    {{-- 3. Main Content Wrapper --}}
    <main class="flex-grow">
        <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
            {{-- Remove extra padding inside content, let slot fill naturally --}}
            <div class="px-4 sm:px-0">
                {{ $slot }}
            </div>
        </div>
    </main>
</div>
@endsection
