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
        <div class="flex items-center justify-between pb-4 border-b border-stroke">
            <a href="{{ route('dashboard') }}" class="text-sm font-semibold text-slate-500 dark:text-slate-300 hover:text-slate-900 dark:text-white flex items-center space-x-2">
                <span class="wire-icon-button w-8 h-8"> <- </span>
                <span>Back to Dashboard</span>
            </a>
            
            {{-- Download Button --}}
            @if(strtolower($status) === 'active')
                <x-button type="primary" href="#">
                    Download Temporary Pass
                </x-button>
            @endif
        </div>

        <h2 class="text-4xl font-hand text-slate-900 dark:text-white flex items-center gap-4">
            Application #{{ $applicationId }}
            <x-status-badge :status="$status" class="ml-4 text-base" />
        </h2>

        {{-- Main Detail Grid (showing details and QR code) --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            {{-- 1. Application Details --}}
            <div class="lg:col-span-2 space-y-6">
                <x-card header="Application Details">
                    <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-6 text-base">
                        <div>
                            <dt class="text-slate-500 dark:text-slate-300">Student Name</dt>
                            <dd class="mt-1 font-semibold text-slate-900 dark:text-white">{{ $studentName }}</dd>
                        </div>
                        <div>
                            <dt class="text-slate-500 dark:text-slate-300">Student ID</dt>
                            <dd class="mt-1 text-slate-900 dark:text-white">{{ $studentId }}</dd>
                        </div>
                        <div>
                            <dt class="text-slate-500 dark:text-slate-300">Pass Type</dt>
                            <dd class="mt-1 text-slate-900 dark:text-white">{{ $passType }}</dd>
                        </div>
                        <div>
                            <dt class="text-slate-500 dark:text-slate-300">Duration</dt>
                            <dd class="mt-1 text-slate-900 dark:text-white">{{ $startDate }} to {{ $endDate }}</dd>
                        </div>
                        <div>
                            <dt class="text-slate-500 dark:text-slate-300">Date Applied</dt>
                            <dd class="mt-1 text-slate-900 dark:text-white">Oct 25, 2025</dd>
                        </div>
                        <div>
                            <dt class="text-slate-500 dark:text-slate-300">Approval Date</dt>
                            <dd class="mt-1 text-slate-900 dark:text-white">Oct 26, 2025</dd>
                        </div>
                    </dl>
                </x-card>
                
                <x-card header="Reason Provided">
                    <p class="text-slate-700 leading-relaxed">{{ $reason }}</p>
                </x-card>

                @php
                    $timeline = [
                        ['label' => 'Submitted', 'date' => 'Nov 28, 2025', 'status' => 'completed'],
                        ['label' => 'Reviewed by Admin', 'date' => 'Nov 29, 2025', 'status' => 'completed'],
                        ['label' => 'Pass Generated', 'date' => 'Nov 30, 2025', 'status' => 'current'],
                        ['label' => 'Archived', 'date' => '—', 'status' => 'upcoming'],
                    ];
                @endphp

                <x-card header="Timeline">
                    <ol class="space-y-4">
                        @foreach($timeline as $step)
                            <li class="flex items-start space-x-4">
                                <span class="w-3 h-3 mt-2 rounded-full {{ $step['status'] === 'completed' ? 'bg-mint' : ($step['status'] === 'current' ? 'bg-iris' : 'bg-stroke') }}"></span>
                                <div>
                                    <p class="text-sm font-semibold text-deep-slate">{{ $step['label'] }}</p>
                                    <p class="text-xs text-warm-gray dark:text-slate-400">{{ $step['date'] }}</p>
                                </div>
                            </li>
                        @endforeach
                    </ol>
                </x-card>
            </div>
            
            {{-- 2. QR Code --}}
            <div class="lg:col-span-1">
                <x-card header="Pass Validation Code" class="h-full">
                    <div class="flex flex-col items-center justify-center space-y-4">
                        <div class="w-full aspect-square border-2 border-dashed border-stroke rounded-2xl flex items-center justify-center text-slate-400">
                            QR Placeholder
                        </div>
                        <p class="text-center text-sm font-medium text-slate-500 dark:text-slate-300">Scan for Validation</p>
                    </div>
                </x-card>
            </div>
        </div>

    </div>

</x-dashboard-layout>
