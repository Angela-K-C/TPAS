{{-- resources/views/guest/application-show.blade.php --}}

<x-dashboard-layout title="Visitor Application Detail" user="Guest">

    @php
        $applicationId = '346tcvbn563456gf';
        $status = 'Active';
        $visitorName = 'Ronald Richards';
        $nationalId = '4600';
        $hostName = 'Admissions Office';
        $hostContact = 'Room 204, Main Building';
        $visitPurpose = 'Visiting campus to accompany a family member during orientation week.';
        $visitStart = '2025-12-01';
        $visitEnd = '2025-12-07';
    @endphp

    <div class="max-w-6xl mx-auto space-y-6">

        <div class="flex items-center justify-between pb-4 border-b border-stroke">
            <a href="{{ route('guest.dashboard') }}" class="text-sm font-semibold text-slate-500 dark:text-slate-300 hover:text-slate-900 dark:text-white flex items-center space-x-2">
                <span class="wire-icon-button w-8 h-8"><-</span>
                <span>Back to Dashboard</span>
            </a>

            <x-button type="primary" href="#">
                Download Visitor's Pass
            </x-button>
        </div>

        <h2 class="text-4xl font-hand text-slate-900 dark:text-white flex items-center gap-4">
            Application #{{ $applicationId }}
            <x-status-badge :status="$status" class="ml-4 text-base" />
        </h2>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 space-y-6">
                <x-card header="Visitor Details">
                    <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-6 text-base">
                        <div>
                            <dt class="text-slate-500 dark:text-slate-300">Visitor Name</dt>
                            <dd class="mt-1 font-semibold text-slate-900 dark:text-white">{{ $visitorName }}</dd>
                        </div>
                        <div>
                            <dt class="text-slate-500 dark:text-slate-300">National ID</dt>
                            <dd class="mt-1 text-slate-900 dark:text-white">{{ $nationalId }}</dd>
                        </div>
                        <div>
                            <dt class="text-slate-500 dark:text-slate-300">Host / Department</dt>
                            <dd class="mt-1 text-slate-900 dark:text-white">{{ $hostName }}</dd>
                        </div>
                        <div>
                            <dt class="text-slate-500 dark:text-slate-300">Host Location</dt>
                            <dd class="mt-1 text-slate-900 dark:text-white">{{ $hostContact }}</dd>
                        </div>
                        <div>
                            <dt class="text-slate-500 dark:text-slate-300">Visit Duration</dt>
                            <dd class="mt-1 text-slate-900 dark:text-white">{{ $visitStart }} to {{ $visitEnd }}</dd>
                        </div>
                    </dl>
                </x-card>

                <x-card header="Visit Purpose">
                    <p class="text-slate-700 leading-relaxed">
                        {{ $visitPurpose }}
                    </p>
                </x-card>

                @php
                    $guestTimeline = [
                        ['label' => 'Invitation Sent', 'date' => 'Nov 27, 2025', 'status' => 'completed'],
                        ['label' => 'Security Review', 'date' => 'Nov 28, 2025', 'status' => 'completed'],
                        ['label' => 'QR Pass Ready', 'date' => 'Nov 29, 2025', 'status' => 'current'],
                    ];
                @endphp

                <x-card header="Visit Checklist">
                    <ol class="space-y-4">
                        @foreach($guestTimeline as $step)
                            <li class="flex items-start space-x-4">
                                <span class="w-3 h-3 mt-2 rounded-full {{ $step['status'] === 'completed' ? 'bg-mint' : 'bg-iris' }}"></span>
                                <div>
                                    <p class="text-sm font-semibold text-deep-slate">{{ $step['label'] }}</p>
                                    <p class="text-xs text-warm-gray dark:text-slate-400">{{ $step['date'] }}</p>
                                </div>
                            </li>
                        @endforeach
                        <li class="flex items-start space-x-4">
                            <span class="w-3 h-3 mt-2 rounded-full bg-stroke"></span>
                            <div>
                                <p class="text-sm font-semibold text-deep-slate">Check-in at Gate 3</p>
                                <p class="text-xs text-warm-gray dark:text-slate-400">Show QR code + ID</p>
                            </div>
                        </li>
                    </ol>
                </x-card>
            </div>

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
