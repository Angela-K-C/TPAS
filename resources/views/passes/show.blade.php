{{-- resources/views/passes/show.blade.php --}}

@php
    $holder = optional($pass->passable)->name ?? 'Guest';
    $reference = 'VST-' . str_pad($pass->id, 4, '0', STR_PAD_LEFT);
@endphp

<x-dashboard-layout title="Pass {{ $reference }}" :user="$userLabel" :logoutRoute="$logoutRoute">
    <div class="max-w-4xl mx-auto space-y-8">
        <x-card header="Pass overview">
            <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-4 text-sm">
                <div>
                    <dt class="font-semibold text-slate-600">Reference</dt>
                    <dd class="text-deep-slate">{{ $reference }}</dd>
                </div>
                <div>
                    <dt class="font-semibold text-slate-600">Holder</dt>
                    <dd class="text-deep-slate">{{ $holder }}</dd>
                </div>
                <div>
                    <dt class="font-semibold text-slate-600">Reason</dt>
                    <dd class="text-deep-slate">{{ $pass->reason_label }}</dd>
                </div>
                <div>
                    <dt class="font-semibold text-slate-600">Status</dt>
                    <dd>
                        <x-status-badge :status="$pass->status" />
                    </dd>
                </div>
                <div>
                    <dt class="font-semibold text-slate-600">Valid from</dt>
                    <dd class="text-deep-slate">{{ optional($pass->valid_from)?->format('M d, Y H:i') ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="font-semibold text-slate-600">Valid until</dt>
                    <dd class="text-deep-slate">{{ optional($pass->valid_until)?->format('M d, Y H:i') ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="font-semibold text-slate-600">Approved by</dt>
                    <dd class="text-deep-slate">{{ $pass->approver->name ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="font-semibold text-slate-600">QR token</dt>
                    <dd class="font-mono text-sm">
                        @if ($pass->qr_code_token)
                            {{ strtoupper(substr($pass->qr_code_token, 0, 8)) }}
                        @else
                            —
                        @endif
                    </dd>
                </div>
            </dl>
        </x-card>

        <x-card header="QR code">
            @if ($pass->qr_code_token)
                <p class="text-sm text-slate-600">
                    Security can scan this QR code or manually enter the token shown in the overview.
                </p>
                <div class="mt-6 flex flex-col items-center gap-4">
                    <img src="{{ route('passes.qr.image', $pass) }}" alt="Temporary pass QR code" class="w-56 h-56 border border-stroke rounded-3xl p-4 bg-white">
                    <a href="{{ route('passes.qr.image', $pass) }}" download="temporary-pass-{{ $pass->id }}.svg" class="text-iris font-semibold hover:text-deep-slate transition">
                        Download QR code
                    </a>
                </div>
            @else
                <p class="text-sm text-warm-gray">QR code not available for this pass.</p>
            @endif
        </x-card>

        @if ($isAdmin)
            <x-card header="Admin actions">
                @if ($pass->status === 'pending')
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <form action="{{ route('passes.update', $pass) }}" method="POST" class="space-y-2">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="status" value="approved">
                            <label class="text-sm text-slate-500">Approve this pass and notify the applicant.</label>
                            <button class="wire-button-primary w-full">
                                Approve
                            </button>
                        </form>

                        <form action="{{ route('passes.update', $pass) }}" method="POST" class="space-y-2">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="status" value="rejected">
                            <label class="text-sm text-slate-500">Reject this request with the default email template.</label>
                            <button class="wire-button-danger w-full">
                                Reject
                            </button>
                        </form>
                    </div>
                @else
                    <p class="text-sm text-warm-gray">This pass has already been {{ $pass->status }}.</p>
                @endif

                <form action="{{ route('passes.destroy', $pass) }}" method="POST" class="mt-6" onsubmit="return confirm('Delete this pass?');">
                    @csrf
                    @method('DELETE')
                    <button class="wire-button w-full text-red-500 hover:text-red-600">
                        Delete pass
                    </button>
                </form>
            </x-card>
        @endif

        <div class="flex justify-between items-center">
            <x-button type="secondary" href="{{ route('passes.index') }}">
                Back to passes
            </x-button>
            <a href="{{ route('passes.qr.image', $pass) }}" class="text-sm font-semibold text-iris hover:text-deep-slate transition">
                Open QR in new tab
            </a>
        </div>
    </div>
</x-dashboard-layout>
