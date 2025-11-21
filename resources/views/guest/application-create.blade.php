{{-- resources/views/guest/application-create.blade.php --}}

<x-dashboard-layout title="New Visitor's Pass Application" :user="auth()->user()?->name ?? 'Guest'">


    <div class="max-w-5xl mx-auto space-y-8">
        <p class="text-slate-500">
            Share your identification and visit details so we can prepare a visitor pass that matches the exact schedule from the wireframes.
        </p>
        @if (session('status'))
            <div class="wire-card border-l-4 border-mint bg-mint/10 p-5">
                <p class="text-sm text-deep-slate">{{ session('status') }}</p>
            </div>
        @endif
        @if ($errors->any())
            <div class="wire-card border-l-4 border-red-500 bg-red-50 p-5 space-y-2">
                <p class="text-sm font-semibold text-red-700">Please fix the following issues:</p>
                <ul class="text-sm text-red-600 list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('guest.application.store') }}" method="POST" class="space-y-8">
            @csrf
           
            @if(session('success'))
        <div class="wire-card border-l-4 border-mint bg-mint/10 p-5">
            <p class="text-sm text-deep-slate">{{ session('success') }}</p>
        </div>
    @endif

            {{-- Visitor Details --}}
            <x-card header="1. Visitor Details">
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <x-input-field id="visitor_name" name="visitor_name" label="Visitor Name" type="text" value="{{ $guest->name ?? '' }}"  helper="Exactly as it appears on the identification card." />
                    <x-input-field id="national_id" name="national_id" label="National ID Number" type="text"  value="{{ $guest->national_id ?? '' }}"  helper="Digits only · e.g. 12345678" />
                    <x-input-field id="email" name="email" label="Email Address" type="email"  value="{{ $guest->email ?? ($email ?? '') }}" helper="We send your QR pass and updates here." />
                    <x-input-field id="phone" name="phone" label="Phone Number" type="tel"  value="{{ $guest->phone ?? '' }}"  helper="Optional but helps our guards reach you if needed." />
                    
                </div>
            </x-card>

            {{-- Visit Details --}}
            <x-card header="2. Visit Details">
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <x-input-field id="host_name" label="Host Name" type="text" name="host_name" helper="Who invited you on campus?" />
                    <x-input-field id="host_department" label="Host Department" type="text" name="host_department" helper="e.g. Admissions, Library, Security" />
                    <x-input-field id="visit_start" label="Visit Start Date" type="date" name="visit_start" helper="You can arrive up to 30 mins before this time." />
                    <x-input-field id="visit_end" label="Visit End Date" type="date" name="visit_end" helper="Passes expire automatically at midnight." />
                    <div class="sm:col-span-2">
                        <x-input-field id="purpose" label="Purpose of Visit" type="textarea" name="purpose" helper="Mention any equipment/luggage you will carry." />
                    </div>
                </div>
            </x-card>

            <div class="flex justify-end space-x-4 pt-4">
                <x-button type="secondary" href="{{ route('guest.dashboard') }}">
                    Cancel
                </x-button>
                <x-button type="primary">
                    Apply for a Visitor's Pass
                </x-button>
            </div>
        </form>
    </div>

</x-dashboard-layout>
