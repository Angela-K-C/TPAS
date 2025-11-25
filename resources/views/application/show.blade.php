{{-- resources/views/application/show.blade.php --}}

<x-dashboard-layout title="Application Detail" :user="auth()->user()->name">

    <div class="max-w-6xl mx-auto space-y-6">

        {{-- Top Header Row --}}
        <div class="flex items-center justify-between pb-4 border-b border-stroke">
            <a href="{{ route('dashboard') }}" class="text-sm font-semibold text-slate-500 dark:text-slate-300 hover:text-slate-900 dark:hover:text-slate-900 flex items-center space-x-2">
                <span class="wire-icon-button w-8 h-8"> ← </span>
                <span>Back to Dashboard</span>
            </a>

            @if(strtolower($application->status) === 'active')
                <x-button type="primary" href="#">
                    Download Temporary Pass
                </x-button>
            @endif
        </div>

        <h2 class="text-4xl font-hand text-slate-900 flex items-center gap-4 flex-wrap">
            Application #TPAS-{{ $application->id }}
            @php
                $expiresIn = $application->valid_until
                    ? \Carbon\Carbon::parse($application->valid_until)->diffForHumans(now(), [
                        'short' => true,
                        'parts' => 2,
                        'syntax' => \Carbon\CarbonInterface::DIFF_ABSOLUTE,
                    ])
                    : null;
                $expired = $application->valid_until && \Carbon\Carbon::parse($application->valid_until)->isPast();
            @endphp
            <div class="flex flex-col gap-1">
                <x-status-badge :status="$application->status" class="ml-0 text-base" />
                @if ($application->valid_until)
                    <span class="text-xs {{ $expired ? 'text-red-600' : 'text-slate-500' }}">
                        {{ $expired ? "Expired {$expiresIn} ago" : "Expires in {$expiresIn}" }}
                    </span>
                @endif
            </div>
        </h2>

        @php
            $resetNotice = $application->status === 'rejected' && str_contains($application->details ?? '', 'Reset by admin');
        @endphp
        @if($resetNotice)
            <div class="rounded-2xl border border-amber-300 bg-amber-50 px-4 py-3 text-sm text-amber-800">
                This pass was reset by an admin and is no longer usable. Please apply again if you need a new pass.
            </div>
        @endif

        {{-- Main Detail Grid --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            {{-- 1. Application Details --}}
            <div class="lg:col-span-2 space-y-6">
                <x-card header="Application Details">
                    <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-6 text-base">
                        <div>
                            <dt class="text-slate-500 dark:text-slate-300">Pass Owner</dt>
                            <dd class="mt-1 font-semibold text-slate-900">
                                {{ class_basename($application->passable_type) }} #{{ $application->passable_id }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-slate-500 dark:text-slate-300">Application ID</dt>
                            <dd class="mt-1 text-slate-900">TPAS-{{ $application->id }}</dd>
                        </div>
                        <div>
                            <dt class="text-slate-500 dark:text-slate-300">Pass Type</dt>
                            <dd class="mt-1 text-slate-900">{{ $application->type ?? 'Temporary Pass' }}</dd>
                        </div>
                        <div>
                            <dt class="text-slate-500 dark:text-slate-300">Duration</dt>
                            <dd class="mt-1 text-slate-900">
                                {{ $application->valid_from->format('Y-m-d') }} to {{ $application->valid_until->format('Y-m-d') }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-slate-500 dark:text-slate-300">Date Applied</dt>
                            <dd class="mt-1 text-slate-900">{{ $application->created_at->format('M d, Y') }}</dd>
                        </div>
                        <div>
                            <dt class="text-slate-500 dark:text-slate-300">Approval Date</dt>
                            <dd class="mt-1 text-slate-900">{{ $application->updated_at->format('M d, Y') }}</dd>
                        </div>
                    </dl>
                </x-card>

                <x-card header="Reason Provided">
                    <p class="text-slate-700 leading-relaxed">{{ $application->reason }}</p>
                </x-card>

                @php
                    $isApproved = $application->status === 'approved';
                    $isRejected = $application->status === 'rejected';
                    $isPending = $application->status === 'pending';
                    $isExpired = $application->valid_until && $application->valid_until->isPast();

                    $timeline = [
                        [
                            'label' => 'Submitted',
                            'date' => optional($application->created_at)?->format('M d, Y') ?? '—',
                            'status' => 'completed',
                        ],
                        [
                            'label' => 'Reviewed by Admin',
                            'date' => $isPending ? '—' : (optional($application->updated_at)?->format('M d, Y') ?? '—'),
                            'status' => $isPending ? 'current' : 'completed',
                        ],
                    ];

                    if ($isRejected) {
                        $timeline[] = [
                            'label' => 'Rejected',
                            'date' => optional($application->updated_at)?->format('M d, Y') ?? '—',
                            'status' => 'completed',
                        ];
                    } else {
                        $timeline[] = [
                            'label' => 'Pass Generated',
                            'date' => optional($application->valid_from)?->format('M d, Y') ?? '—',
                            'status' => $isApproved ? ($isExpired ? 'completed' : 'current') : 'upcoming',
                        ];

                        if ($application->valid_until) {
                            $timeline[] = [
                                'label' => $isExpired ? 'Expired' : 'Archived',
                                'date' => optional($application->valid_until)?->format('M d, Y') ?? '—',
                                'status' => $isExpired ? 'completed' : ($isApproved ? 'upcoming' : 'upcoming'),
                            ];
                        }
                    }
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

                        <p class="text-center text-sm font-medium text-slate-500 dark:text-slate-300">Scan for Validation</p>
                    </div>
                </x-card>
            </div>
        </div>

    </div>

</x-dashboard-layout>
