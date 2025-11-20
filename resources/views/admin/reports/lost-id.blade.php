{{-- resources/views/admin/reports/lost-id.blade.php --}}

<x-dashboard-layout title="Reported Lost IDs" user="Admin">

    {{-- Header and Back Link --}}
    <div class="flex items-center justify-between mb-6 border-b pb-4">
        <a href="{{ route('admin.dashboard') }}" class="text-sm font-medium text-slate-500 hover:text-iris flex items-center space-x-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            <span>Back to Dashboard</span>
        </a>
    </div>

    {{-- <h2 class="text-4xl font-hand text-slate-900 dark:text-white mb-6">
        Lost ID Report Log
    </h2> --}}

    <x-card header="Reported IDs Requiring Action">
        
        {{-- Search Bar Placeholder --}}
        <div class="mb-4">
            <input type="search" placeholder="Search by Student ID, Name, or Location..." 
                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-iris focus:ring-iris sm:text-sm">
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Report Date</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Student ID / Name</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Last Seen Date</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Reported Location</th>
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
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-center text-slate-500">No lost ID reports found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-card>

</x-dashboard-layout>