{{-- resources/views/admin/applications/review.blade.php --}}

@php
    /** @var \App\Models\TemporaryPass $application */
    $holder = $application->passable;
    $status = ucfirst($application->status);
@endphp

<x-dashboard-layout title="Application Review #{{ $application->id }}" user="Admin">

    <div class="max-w-6xl mx-auto space-y-6">
        @if (session('success'))
            <div class="rounded-2xl border border-mint bg-mint/10 px-4 py-3 text-sm text-green-700">
                {{ session('success') }}
            </div>
        @endif

        <div class="flex items-center justify-between pb-4 border-b border-stroke">
            <a href="{{ route('admin.applications.manage') }}" class="text-sm font-semibold text-slate-500 hover:text-iris flex items-center space-x-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                <span>Back to Applications</span>
            </a>

        </div>

        <h2 class="text-4xl font-hand text-slate-900 flex items-center gap-4">
            Application #{{ $application->id }}
            <x-status-badge :status="$application->status" class="ml-4 text-base" />
        </h2>

        @php
            $resetNotice = $application->status === 'rejected' && str_contains($application->details ?? '', 'Reset by admin');
        @endphp
        @if($resetNotice)
            <div class="rounded-2xl border border-amber-300 bg-amber-50 px-4 py-3 text-sm text-amber-800">
                This pass was reset and is no longer usable. Issue a new approval if the user needs access again.
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 space-y-6">
                <x-card header="Applicant Details">
                    <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-6 text-base">
                        @if($holder instanceof \App\Models\Guest && $holder->profile_image_path)
                        <div class="sm:col-span-2 flex items-center gap-4 mb-2">
                            <img src="{{ asset('storage/' . $holder->profile_image_path) }}" alt="Profile Photo" class="w-24 h-24 rounded-full object-cover border border-slate-200 shadow" />
                            <span class="text-slate-600 text-sm">Profile Photo</span>
                        </div>
                        @endif
                        <div>
                            <dt class="text-slate-500">Applicant Name</dt>
                            <dd class="mt-1 font-semibold text-slate-900">{{ $holder->name ?? '—' }}</dd>
                        </div>
                        <div>
                            <dt class="text-slate-500">Applicant Email</dt>
                            <dd class="mt-1 text-slate-900">{{ $holder->email ?? '—' }}</dd>
                        </div>
                        <div>
                            <dt class="text-slate-500">User Type</dt>
                            <dd class="mt-1 text-slate-900">{{ class_basename($application->passable_type) }}</dd>
                        </div>
                        <div>
                            <dt class="text-slate-500">Submitted</dt>
                            <dd class="mt-1 text-slate-900">{{ $application->created_at?->format('M d, Y H:i') ?? '—' }}</dd>
                        </div>
                        <div>
                            <dt class="text-slate-500">Valid From</dt>
                            <dd class="mt-1 text-slate-900">{{ $application->valid_from?->format('M d, Y H:i') ?? 'Pending approval' }}</dd>
                        </div>
                        <div>
                            <dt class="text-slate-500">Valid Until</dt>
                            <dd class="mt-1 text-slate-900">{{ $application->valid_until?->format('M d, Y H:i') ?? 'Pending approval' }}</dd>
                        </div>
                        @if($application->approver)
                            <div>
                                <dt class="text-slate-500">Approved By</dt>
                                <dd class="mt-1 text-slate-900">{{ $application->approver->name }}</dd>
                            </div>
                        @endif
                    </dl>
                </x-card>

                <x-card header="Reason Provided">
                    <p class="text-slate-700 leading-relaxed">
                        {{ $application->reason_label }}
                    </p>
                </x-card>

                @if($application->auditLogs->isNotEmpty())
                    <x-card header="Audit Trail">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 text-sm">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                            When
                                        </th>
                                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                            Admin
                                        </th>
                                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                            Action
                                        </th>
                                        <th class="px-4 py-2 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                            From → To
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach ($application->auditLogs as $log)
                                        @php
                                            $beforeStatus = $log->changes['before']['status'] ?? '—';
                                            $afterStatus = $log->changes['after']['status'] ?? '—';
                                        @endphp
                                        <tr>
                                            <td class="px-4 py-2 whitespace-nowrap text-slate-700">
                                                {{ $log->created_at?->format('M d, Y H:i') ?? '—' }}
                                            </td>
                                            <td class="px-4 py-2 whitespace-nowrap text-slate-700">
                                                {{ $log->admin->name ?? '—' }}
                                            </td>
                                            <td class="px-4 py-2 whitespace-nowrap text-slate-700">
                                                {{ str_replace('_', ' ', ucfirst($log->action)) }}
                                            </td>
                                            <td class="px-4 py-2 whitespace-nowrap text-slate-700">
                                                {{ ucfirst($beforeStatus) }} → {{ ucfirst($afterStatus) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </x-card>
                @endif
            </div>

            <div class="space-y-6">
                @if($application->status === 'pending')
                    <x-card header="Decision Panel">
                        <p class="text-sm text-slate-500 mb-4">Set the final status for this temporary pass.</p>
                        <div class="flex flex-col gap-3">
                            <form action="{{ route('passes.update', $application) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status" value="approved">
                                <x-button type="primary" class="w-full">
                                    Approve Application
                                </x-button>
                            </form>
                            <form action="{{ route('passes.update', $application) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status" value="rejected">
                                <x-button type="secondary" class="w-full border border-red-500 text-red-600 hover:bg-red-50">
                                    Reject Application
                                </x-button>
                            </form>
                        </div>
                    </x-card>
                @else
                    <x-card header="Current Pass Status">
                        <div class="space-y-4 text-center">
                            <p class="text-lg font-semibold text-slate-700">Application {{ ucfirst($application->status) }}</p>
                            @if($application->status === 'approved' && $application->qr_code_token)
                                <img src="{{ route('passes.qr.image', $application) }}" alt="QR code" class="w-48 h-48 mx-auto bg-white p-3 rounded-2xl shadow">
                                <p class="text-sm text-slate-500">Token: {{ strtoupper(substr($application->qr_code_token,0,8)) }}</p>
                            @else
                                <p class="text-sm text-slate-500">No QR code available for this status.</p>
                            @endif
                        </div>
                    </x-card>
                @endif
            </div>
        </div>
    </div>

</x-dashboard-layout>
