{{-- resources/views/admin/dashboard.blade.php --}}

<x-dashboard-layout title="Admin Dashboard" 
user="Admin"
:logoutRoute="route('admin.logout')">

    {{-- --- Top Action Buttons --- --}}
    <div class="flex flex-col sm:flex-row justify-end items-stretch sm:items-center gap-3 mb-6">
        <x-button type="primary" href="{{ route('admin.applications.manage') }}" class="flex-1 sm:flex-none">
            Manage Applications
        </x-button>

        <x-button type="secondary" href="{{ route('admin.passes.expired') }}" class="flex-1 sm:flex-none">
            Expired Passes
        </x-button>

        <x-button type="warning" href="{{ route('admin.reports.lost.id') }}" class="flex-1 sm:flex-none">
            Lost ID Reports
        </x-button>
    </div>

    {{-- --- Key Metric Cards (smaller) --- --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        @php
            $cards = [
                ['title' => 'Pending Applications', 'count' => $metrics['pending'] ?? 0, 'color' => 'text-iris', 'route' => route('admin.applications.manage', ['status' => 'Pending'])],
                ['title' => 'Approved Passes', 'count' => $metrics['approved'] ?? 0, 'color' => 'text-green-600', 'route' => route('admin.applications.manage', ['status' => 'Approved'])],
                ['title' => 'Rejected Applications', 'count' => $metrics['rejected'] ?? 0, 'color' => 'text-red-600', 'route' => route('admin.applications.manage', ['status' => 'Rejected'])],
                ['title' => 'Expired Passes', 'count' => $metrics['expired'] ?? 0, 'color' => 'text-gray-500', 'route' => route('admin.passes.expired')],
            ];
        @endphp

        @foreach ($cards as $metric)
            <x-card class="shadow-md hover:shadow-lg transition duration-150 rounded-xl p-4">
                <div class="flex flex-col space-y-1">
                    <p class="text-2xl sm:text-3xl font-bold {{ $metric['color'] }}">{{ $metric['count'] }}</p>
                    <h3 class="text-base font-medium text-slate-700">{{ $metric['title'] }}</h3>
                    <a href="{{ $metric['route'] }}" class="text-sm font-medium text-slate-400 hover:text-iris mt-1 inline-block">View all →</a>
                </div>
            </x-card>
        @endforeach
    </div>

    {{-- --- Recent Activities Table --- --}}
    <x-card header="Recent Activities (Last 5)">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">#</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Application ID</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Admin. no</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Duration</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4"></th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200 text-sm">
                    @forelse ($passes as $app)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">{{ $app->id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-slate-700 font-medium">VST-{{ $app->id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-slate-700">{{ $app->passable_id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-slate-700">
                                @if($app->valid_from && $app->valid_until)
                                    {{ $app->valid_from->format('M d') }} - {{ $app->valid_until->format('M d') }}
                                @else
                                    —
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <x-status-badge :status="$app->status" />
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                <a href="{{ route('admin.applications.review', ['application' => $app->id]) }}" class="text-iris font-medium hover:underline">
                                    Review
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-slate-500">No recent activity.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-card>

</x-dashboard-layout>
