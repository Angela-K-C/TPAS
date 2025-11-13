{{-- resources/views/dashboard.blade.php --}}

<x-dashboard-layout title="Dashboard">

    <div class="space-y-8">

        {{-- Hero strip with action buttons --}}
        <div class="wire-card p-6">
            <div class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
                <div class="flex flex-col sm:flex-row gap-3 sm:justify-end w-full sm:w-auto">
                    <x-button type="primary" class="flex-1 sm:flex-none" href="{{ route('application.create') }}">
                        Apply for a Temporary Pass
                    </x-button>
                    <x-button type="secondary" class="flex-1 sm:flex-none" href="{{ route('report.lost.id') }}">
                        Report Lost ID
                    </x-button>
                </div>
            </div>
        </div>

        {{-- Application History Table --}}
        <x-card header="My Pass Applications">
            @if ($passes->isEmpty())
                <div class="text-center py-12 space-y-4">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-lilac/60 text-iris text-2xl font-hand">
                        
                    </div>
                    <div>
                        <p class="text-lg font-semibold text-deep-slate">No applications yet</p>
                        <p class="text-sm text-warm-gray">Your future requests will appear here once submitted.</p>
                    </div>
                    <x-button type="primary" href="{{ route('application.create') }}">
                        Start a new application
                    </x-button>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="wire-table">
                        <thead class="bg-slate-50 text-xs uppercase text-warm-gray tracking-widest">
                            <tr>
                                <th class="px-6 py-4 text-left">#</th>
                                <th class="px-6 py-4 text-left">Application ID</th>
                                <th class="px-6 py-4 text-left">Admn. no</th>
                                <th class="px-6 py-4 text-left">Type</th>
                                <th class="px-6 py-4 text-left">Duration</th>
                                <th class="px-6 py-4 text-left">Status</th>
                                <th class="px-6 py-4"></th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-stroke text-sm">
                            @foreach ($passes as $pass)
                                <tr>
                                    <td class="px-6 py-4 font-semibold text-deep-slate">{{ $pass->id }}</td>
                                    <td class="px-6 py-4 text-deep-slate">TPAS-{{ $pass->id }}</td>
                                    <td class="px-6 py-4 text-deep-slate">{{ $pass->passable_id }}</td>
                                    <td class="px-6 py-4 text-warm-gray">{{ class_basename($pass->passable_type) }}</td>
                                    <td class="px-6 py-4 text-warm-gray">
                                        {{ $pass->valid_from->format('M d') }} - {{ $pass->valid_until->format('M d, Y') }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <x-status-badge :status="$pass->status" />
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <a href="{{ route('application.show', ['application' => $pass->id]) }}" class="text-iris font-semibold">
                                            View
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </x-card>

    </div>

</x-dashboard-layout>
