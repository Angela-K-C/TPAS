{{-- resources/views/application/show.blade.php --}}

<x-dashboard-layout title="Application Detail">

    {{-- Dummy data for structure review (replace with actual PHP variables) --}}
    @php
        $applicationId = '346tcvbn563456gf';
        $status = 'Active'; 
        $studentName = 'Ronald Richards';
        $studentId = '4600';
        $passType = 'Temporary Visitor Access';
        $startDate = '2025-12-01';
        $endDate = '2025-12-07';
        $reason = 'Temporary permit for family visit during holidays.';
    @endphp

    <div class="max-w-6xl mx-auto space-y-6">

        {{-- Top Header Row --}}
        <div class="flex items-center justify-between pb-4 border-b border-gray-200">
            <a href="{{ route('dashboard') }}" class="text-sm font-medium text-brand-muted hover:text-brand-primary flex items-center space-x-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                <span>Back to Dashboard</span>
            </a>
            
            {{-- Download Button --}}
            @if(strtolower($status) === 'active')
                <x-button type="primary" href="#" class="bg-brand-secondary">
                    Download Temporary Pass
                </x-button>
            @endif
        </div>

        <h2 class="text-3xl font-extrabold text-brand-text">
            Application #{{ $applicationId }}
            <x-status-badge :status="$status" class="ml-4 text-base" />
        </h2>

        {{-- Main Detail Grid (showing details and QR code) --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            {{-- 1. Application Details --}}
            <div class="lg:col-span-2 space-y-6">
                <x-card header="Application Details">
                    <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4 text-sm">
                        <div><dt class="font-medium text-brand-muted">Student Name</dt><dd class="mt-1 text-brand-text font-semibold">{{ $studentName }}</dd></div>
                        <div><dt class="font-medium text-brand-muted">Student ID</dt><dd class="mt-1 text-brand-text">{{ $studentId }}</dd></div>
                        <div><dt class="font-medium text-brand-muted">Pass Type</dt><dd class="mt-1 text-brand-text">{{ $passType }}</dd></div>
                        <div><dt class="font-medium text-brand-muted">Duration</dt><dd class="mt-1 text-brand-text">{{ $startDate }} to {{ $endDate }}</dd></div>
                        <div><dt class="font-medium text-brand-muted">Date Applied</dt><dd class="mt-1 text-brand-text">Oct 25, 2025</dd></div>
                        <div><dt class="font-medium text-brand-muted">Approval Date</dt><dd class="mt-1 text-green-700 font-semibold">Oct 26, 2025</dd></div>
                    </dl>
                </x-card>
                
                <x-card header="Reason Provided">
                    <p class="text-brand-text italic">{{ $reason }}</p>
                </x-card>
            </div>
            
            {{-- 2. QR Code --}}
            <div class="lg:col-span-1">
                <x-card header="Pass Validation Code" class="h-full">
                    <div class="flex flex-col items-center justify-center space-y-4">
                        <div class="bg-gray-100 p-8 rounded-lg">
                                                    </div>
                        <p class="text-center text-sm font-medium text-brand-muted">Scan for Validation</p>
                    </div>
                </x-card>
            </div>
        </div>

    </div>

</x-dashboard-layout>