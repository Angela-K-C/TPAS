{{-- resources/views/guest/dashboard.blade.php --}}

<x-dashboard-layout title="Guest Dashboard" user="Guest">

    <div class="space-y-8">
        {{-- Navigation Strip --}}
        <div class="wire-card p-6">
            <div class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
                <div class="flex items-center flex-wrap gap-3">
                    <button type="button"
                        class="px-4 py-2 rounded-full text-sm font-semibold bg-iris text-white">
                        Guest Dashboard
                    </button>
                </div>
                <div class="flex flex-col sm:flex-row gap-3 sm:justify-end w-full sm:w-auto">
                    <x-button type="primary" class="flex-1 sm:flex-none" href="{{ route('guest.application.create') }}">
                        Apply for a Visitor's Pass
                    </x-button>
                </div>
            </div>
        </div>

        {{-- Guest Applications Table --}}
        <x-card header="Guest Applications">
            @php
                $guestApplications = [
                    (object)['id' => 1, 'app_id' => 'VST-5120', 'national_id' => '37894561', 'duration' => 'Mar 03 - Mar 05, 2025', 'status' => 'Active', 'purpose' => 'Family Visit'],
                    (object)['id' => 2, 'app_id' => 'VST-4988', 'national_id' => '57940213', 'duration' => 'Mar 12 - Mar 12, 2025', 'status' => 'Pending', 'purpose' => 'Interview'],
                    (object)['id' => 3, 'app_id' => 'VST-4600', 'national_id' => '15975368', 'duration' => 'Feb 24 - Feb 24, 2025', 'status' => 'Inactive', 'purpose' => 'Orientation'],
                ];
            @endphp

            @if(count($guestApplications) === 0)
                <div class="text-center py-12 space-y-4">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-mint/60 text-deep-slate text-2xl font-hand">
                        👋
                    </div>
                    <div>
                        <p class="text-lg font-semibold text-deep-slate">No visitor passes yet</p>
                        <p class="text-sm text-warm-gray">Start an application to receive a QR pass for campus access.</p>
                    </div>
                    <x-button type="primary" href="{{ route('guest.application.create') }}">
                        Apply for a Visitor's Pass
                    </x-button>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="wire-table">
                        <thead class="bg-slate-50 text-xs uppercase text-warm-gray tracking-widest">
                            <tr>
                                <th class="px-6 py-4 text-left">#</th>
                                <th class="px-6 py-4 text-left">Application ID</th>
                                <th class="px-6 py-4 text-left">National ID No.</th>
                                <th class="px-6 py-4 text-left">Purpose</th>
                                <th class="px-6 py-4 text-left">Duration</th>
                                <th class="px-6 py-4 text-left">Status</th>
                                <th class="px-6 py-4"></th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-stroke text-sm">
                            @foreach ($guestApplications as $application)
                                <tr>
                                    <td class="px-6 py-4 font-semibold text-deep-slate">{{ $application->id }}</td>
                                    <td class="px-6 py-4 text-deep-slate">{{ $application->app_id }}</td>
                                    <td class="px-6 py-4 text-deep-slate">{{ $application->national_id }}</td>
                                    <td class="px-6 py-4 text-warm-gray">{{ $application->purpose }}</td>
                                    <td class="px-6 py-4 text-warm-gray">{{ $application->duration }}</td>
                                    <td class="px-6 py-4">
                                        <x-status-badge :status="$application->status" />
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <a href="{{ route('guest.application.show', ['application' => $application->app_id]) }}" class="text-iris font-semibold">
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
