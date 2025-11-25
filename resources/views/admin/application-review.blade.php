<x-dashboard-layout title="Review Application" user="Admin">

    <div class="max-w-4xl mx-auto space-y-6">

        <div class="flex items-center justify-between pb-4 border-b border-stroke">
            <a href="{{ route('admin.dashboard') }}" class="text-sm font-semibold text-slate-500 flex items-center space-x-2">
                <span class="wire-icon-button w-8 h-8">←</span>
                <span>Back to Dashboard</span>
            </a>

            <div class="flex gap-3">
                <form method="POST" action="{{ route('passes.update', $application) }}">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="status" value="approved">
                    <x-button type="primary">Approve</x-button>
                </form>
                <form method="POST" action="{{ route('passes.update', $application) }}">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="status" value="rejected">
                    <x-button type="danger">Reject</x-button>
                </form>
            </div>
        </div>

        <h2 class="text-3xl font-bold text-slate-900">Application #VST-{{ $application->id }}</h2>
        <x-status-badge :status="$application->status" class="mt-2" />

        <x-card header="Visitor Details">
            <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-6 text-base">
                <div>
                    <dt class="text-slate-500">Passable Type</dt>
                    <dd class="mt-1 font-semibold text-slate-900">{{ class_basename($application->passable_type) }}</dd>
                </div>
                <div>
                    <dt class="text-slate-500">Passable ID</dt>
                    <dd class="mt-1 text-slate-900">{{ $application->passable_id }}</dd>
                </div>
                <div>
                    <dt class="text-slate-500">Host</dt>
                    <dd class="mt-1 text-slate-900">{{ $application->host_name ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="text-slate-500">Location</dt>
                    <dd class="mt-1 text-slate-900">{{ $application->host_location ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="text-slate-500">Duration</dt>
                    <dd class="mt-1 text-slate-900">
                        @if ($application->valid_from && $application->valid_until)
                            {{ $application->valid_from->format('Y-m-d') }} to {{ $application->valid_until->format('Y-m-d') }}
                        @else
                            —
                        @endif
                    </dd>
                </div>
            </dl>
        </x-card>

        <x-card header="Visit Purpose">
            <p class="text-slate-700 leading-relaxed">{{ $application->reason }}</p>
        </x-card>

    </div>

</x-dashboard-layout>
