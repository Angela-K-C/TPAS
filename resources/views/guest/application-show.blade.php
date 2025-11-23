<x-dashboard-layout title="Visitor Application Detail" :user="auth('guest')->user()?->name ?? 'Guest'">

    <div class="max-w-6xl mx-auto space-y-6">

        <div class="flex items-center justify-between pb-4 border-b border-stroke">
            <a href="{{ route('guest.dashboard') }}" class="text-sm font-semibold text-slate-500 flex items-center space-x-2">
                <span class="wire-icon-button w-8 h-8">←</span>
                <span>Back to Dashboard</span>
            </a>

            @if($application->status === 'approved' && $application->qr_code_token)
                <x-button type="primary" href="{{ route('passes.qr.pdf', $application) }}">
                    Download Visitor's Pass
                </x-button>
            @endif
        </div>

        <h2 class="text-4xl font-hand text-slate-900 flex items-center gap-4">
            Application #VST-{{ $application->id }}
            <x-status-badge :status="$application->status" class="ml-4 text-base" />
        </h2>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 space-y-6">
                <x-card header="Visitor Details">
                    <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-6 text-base">
                        <div>
                            <dt class="text-slate-500">Visitor Name</dt>
                            <dd class="mt-1 font-semibold text-slate-900">{{ $application->passable->name ?? 'Guest' }}</dd>
                        </div>
                        <div>
                            <dt class="text-slate-500">National ID</dt>
                            <dd class="mt-1 text-slate-900">{{ $application->national_id ?? 'N/A' }}</dd>
                        </div>
                        <div>
                            <dt class="text-slate-500">Host Name</dt>
                            <dd class="mt-1 text-slate-900">{{ $application->host_name ?? '—' }}</dd>
                        </div>
                        <div>
                            <dt class="text-slate-500">Host Department</dt>
                            <dd class="mt-1 text-slate-900">{{ $application->host_department ?? '—' }}</dd>
                        </div>
                        <div>
                            <dt class="text-slate-500">Visit Duration</dt>
                            <dd class="mt-1 text-slate-900">
                               {{ $application->valid_from?->format('Y-m-d') ?? 'N/A' }} to {{ $application->valid_until?->format('Y-m-d') ?? 'N/A' }}
                            </dd>
                        </div>
                    </dl>
                </x-card>

                <x-card header="Visit Purpose">
                    <p class="text-slate-700 leading-relaxed">
                        {{ $application->purpose ?? '—' }}
                    </p>
                </x-card>

                @php
                    $guestTimeline = [
                        ['label' => 'Invitation Sent', 'date' => $application->created_at?->format('M d, Y') ?? 'N/A', 'status' => 'completed'],
                        ['label' => 'Security Review', 'date' => $application->updated_at?->format('M d, Y') ?? 'N/A', 'status' => 'completed'],
                        ['label' => 'QR Pass Ready', 'date' => $application->valid_from?->format('M d, Y') ?? 'pending', 'status' => 'current'],
                    ];
                @endphp

                <x-card header="Visit Checklist">
                    <ol class="space-y-4">
                        @foreach($guestTimeline as $step)
                            <li class="flex items-start space-x-4">
                                <span class="w-3 h-3 mt-2 rounded-full {{ $step['status'] === 'completed' ? 'bg-mint' : 'bg-iris' }}"></span>
                                <div>
                                    <p class="text-sm font-semibold text-deep-slate">{{ $step['label'] }}</p>
                                    <p class="text-xs text-warm-gray">{{ $step['date'] }}</p>
                                </div>
                            </li>
                        @endforeach
                        <li class="flex items-start space-x-4">
                            <span class="w-3 h-3 mt-2 rounded-full bg-stroke"></span>
                            <div>
                                <p class="text-sm font-semibold text-deep-slate">Check-in at Gate 3</p>
                                <p class="text-xs text-warm-gray">Show QR code + ID</p>
                            </div>
                        </li>
                    </ol>
                </x-card>
            </div>

            <div class="lg:col-span-1">
                <x-card header="Pass Validation Code" class="h-full">
                    <div class="flex flex-col items-center justify-center space-y-4">
                        @if($application->status === 'approved' && $application->qr_code_token)
                            <img
                                src="{{ route('passes.qr.image', $application) }}"
                                alt="Pass QR code"
                                class="w-full max-w-xs aspect-square bg-white p-4 rounded-2xl shadow"
                            >
                            <p class="text-center text-xs font-mono text-slate-500">
                                Token: {{ strtoupper(substr($application->qr_code_token, 0, 8)) }}
                            </p>
                            <a
                                href="{{ route('passes.qr.image', $application) }}"
                                target="_blank"
                                class="text-sm font-semibold text-iris hover:text-deep-slate transition"
                            >
                                Open QR in new tab
                            </a>
                            <a
                                href="{{ route('passes.qr.pdf', $application) }}"
                                class="text-sm font-semibold text-iris hover:text-deep-slate transition"
                            >
                                Download QR as PDF
                            </a>
                        @else
                            <div class="w-full aspect-square border-2 border-dashed border-stroke rounded-2xl flex items-center justify-center text-slate-400 text-center px-4">
                                QR code will appear here once your pass is approved.
                            </div>
                        @endif
                        <p class="text-center text-sm font-medium text-slate-500">Scan for Validation</p>
                    </div>
                </x-card>
            </div>
        </div>

    </div>

</x-dashboard-layout>
