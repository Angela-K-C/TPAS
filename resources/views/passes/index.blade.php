{{-- resources/views/passes/index.blade.php --}}

<x-dashboard-layout title="Temporary Passes" :user="$userLabel" :logoutRoute="$logoutRoute">
    <div class="space-y-8">
        @if (session('success'))
            <div class="wire-card border-l-4 border-mint bg-mint/10 p-5">
                <p class="text-sm text-deep-slate">{{ session('success') }}</p>
            </div>
        @endif

        <x-card header="Need a new pass?">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <p class="text-slate-600">
                        Manage every pass request from one place. Track approvals, download QR codes, and keep guards in the loop.
                    </p>
                </div>
                @if ($canApply)
                    <x-button type="primary" href="{{ route('passes.create') }}">
                        Apply for a pass
                    </x-button>
                @else
                    <span class="text-sm text-warm-gray">
                        Only students and guests can create new pass requests.
                    </span>
                @endif
            </div>
        </x-card>

        <x-card header="Pass history">
            @if ($passes->isEmpty())
                <div class="py-12 text-center space-y-4">
                    <p class="text-lg font-semibold text-deep-slate">No passes yet</p>
                    <p class="text-sm text-warm-gray">Once you apply for a pass, it will appear in this table.</p>
                    @if ($canApply)
                        <x-button type="primary" href="{{ route('passes.create') }}">
                            Start your first application
                        </x-button>
                    @endif
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="wire-table">
                        <thead class="bg-slate-50 text-xs uppercase text-warm-gray tracking-widest">
                            <tr>
                                <th class="px-6 py-4 text-left">Reference</th>
                                <th class="px-6 py-4 text-left">Holder</th>
                                <th class="px-6 py-4 text-left">Reason</th>
                                <th class="px-6 py-4 text-left">Status</th>
                                <th class="px-6 py-4 text-left">Valid From</th>
                                <th class="px-6 py-4 text-left">Valid Until</th>
                                <th class="px-6 py-4 text-left">Actions</th>
                                @if ($isAdmin)
                                    <th class="px-6 py-4 text-left">Manage</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-stroke text-sm">
                            @foreach ($passes as $pass)
                                <tr>
                                    <td class="px-6 py-4 font-semibold text-deep-slate">VST-{{ str_pad($pass->id, 4, '0', STR_PAD_LEFT) }}</td>
                                    <td class="px-6 py-4 text-slate-600">{{ optional($pass->passable)->name ?? 'Guest' }}</td>
                                <td class="px-6 py-4 text-slate-600">{{ $pass->reason_label }}</td>
                                <td class="px-6 py-4">
                                    @php
                                        $expiresIn = $pass->valid_until
                                            ? \Carbon\Carbon::parse($pass->valid_until)->diffForHumans(now(), [
                                                'short' => true,
                                                'parts' => 2,
                                                'syntax' => \Carbon\CarbonInterface::DIFF_ABSOLUTE,
                                            ])
                                            : null;
                                        $expired = $pass->valid_until && \Carbon\Carbon::parse($pass->valid_until)->isPast();
                                    @endphp
                                    <div class="flex flex-col gap-1">
                                        <x-status-badge :status="$pass->status" />
                                        @if ($pass->valid_until)
                                            <span class="text-xs {{ $expired ? 'text-red-600' : 'text-slate-500' }}">
                                                {{ $expired ? "Expired {$expiresIn} ago" : "Expires in {$expiresIn}" }}
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-slate-600">
                                    {{ optional($pass->valid_from)?->format('M d, Y H:i') ?? '—' }}
                                </td>
                                    <td class="px-6 py-4 text-slate-600">
                                        {{ optional($pass->valid_until)?->format('M d, Y H:i') ?? '—' }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <a href="{{ route('passes.show', $pass) }}" class="text-iris font-semibold hover:text-deep-slate transition">
                                            View details
                                        </a>
                                    </td>
                                    @if ($isAdmin)
                                        <td class="px-6 py-4 space-y-2">
                                            @if ($pass->status === 'pending')
                                                <form action="{{ route('passes.update', $pass) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="status" value="approved">
                                                    <button class="wire-button-primary w-full text-xs">
                                                        Approve
                                                    </button>
                                                </form>
                                                <form action="{{ route('passes.update', $pass) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="status" value="rejected">
                                                    <button class="wire-button-danger w-full text-xs">
                                                        Reject
                                                    </button>
                                                </form>
                                            @else
                                                <p class="text-sm text-slate-500">No actions available</p>
                                            @endif
                                            <form action="{{ route('passes.destroy', $pass) }}" method="POST" onsubmit="return confirm('Delete this application?');">
                                                @csrf
                                                @method('DELETE')
                                                <button class="wire-button w-full text-xs text-red-500 hover:text-red-600">
                                                    Delete
                                                </button>
                                            </form>
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </x-card>
    </div>
</x-dashboard-layout>
