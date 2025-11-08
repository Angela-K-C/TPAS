{{-- resources/views/components/dashboard-layout.blade.php --}}

@props(['title' => 'Dashboard'])

@extends('layouts.app')

@section('content')
<div class="min-h-screen flex flex-col">
    
    {{-- 1. Navigation Bar: Now called as a Component --}}
    <x-navbar />
    
    {{-- 2. Page Header --}}
    <header class="bg-white shadow border-b border-gray-100">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold leading-tight text-brand-text">
                {{ $title }}
            </h1>
        </div>
    </header>

    {{-- 3. Main Content Wrapper --}}
    <main class="flex-grow bg-brand-bg">
        <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
            <div class="px-4 py-6 sm:px-0">
                {{ $slot }}
            </div>
        </div>
    </main>
</div>
@endsection