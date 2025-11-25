{{-- resources/views/admin/reports/lost-id.blade.php --}}

<x-dashboard-layout title="Reported Lost IDs" user="Admin">

    {{-- Header and Back Link --}}
    <div class="flex items-center justify-between mb-6 border-b pb-4">
        <a href="{{ route('admin.dashboard') }}" class="text-sm font-medium text-slate-500 hover:text-iris flex items-center space-x-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            <span>Back to Dashboard</span>
        </a>
    </div>

    {{-- Quick reset form --}}
    <x-card class="mb-6" header="Reset passes by admission number or email">
        <form action="{{ route('admin.passes.reset.identifier') }}" method="POST" class="flex flex-col gap-3 md:flex-row md:items-center">
            @csrf
            <label for="identifier" class="text-sm font-medium text-slate-700">Admission number or email</label>
            <input
                id="identifier"
                name="identifier"
                type="text"
                required
                class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-iris focus:ring-iris sm:text-sm"
                placeholder="e.g. 123456 or user@example.com"
            >
            <button type="submit" class="inline-flex items-center justify-center rounded-md bg-iris px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-600">
                Reset passes
            </button>
        </form>
    </x-card>

    <x-card header="Reported IDs Requiring Action">

        {{-- Search Bar --}}
        <form method="GET" action="{{ route('admin.reports.lost.id') }}" class="mb-4">
            <label for="lost-search" class="sr-only">Search lost ID reports</label>
            <div class="relative">
                <input
                    id="lost-search"
                    type="search"
                    name="q"
                    value="{{ $search ?? '' }}"
                    placeholder="Search by Student ID, Name, or Location..."
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-iris focus:ring-iris sm:text-sm pl-9"
                >
                <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M10 4a6 6 0 014.472 9.89l3.819 3.82a1 1 0 01-1.414 1.414l-3.82-3.819A6 6 0 1110 4z"/>
                    </svg>
                </span>
            </div>
        </form>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Report Date</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Student ID / Name</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Last Seen Date</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Reported Location</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                        <!-- Removed Status column -->
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200 text-sm">
                    @forelse ($reports as $report)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">{{ $report->created_at->format('M d, Y H:i') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-slate-700">
                                {{ $report->visitor_name }} ({{ $report->national_id }})
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-slate-700">{{ $report->valid_from->format('M d, Y') }}</td>
                            <td class="px-6 py-4 text-slate-700">{{ $report->details }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <a href="{{ route('admin.applications.review', $report) }}" class="text-brand-primary font-semibold text-sm hover:underline">
                                        Review
                                    </a>
                                    <form action="{{ route('admin.passes.reset', $report) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="text-iris hover:text-indigo-700 font-semibold text-sm">
                                            Reset temporary pass
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-slate-500">No lost ID reports found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-card>

</x-dashboard-layout>
