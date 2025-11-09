{{-- resources/views/admin/passes/expired.blade.php --}}

<x-dashboard-layout title="Expired Passes" user="Admin">

    {{-- --- Back to Dashboard --- --}}
    <div class="mb-6 border-b pb-4">
        <a href="{{ route('admin.dashboard') }}" class="text-sm font-medium text-slate-500 hover:text-brand-primary flex items-center space-x-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            <span>Back to Dashboard</span>
        </a>
    </div>

    {{-- --- Expired Passes Table --- --}}
    <x-card header="List of Expired Temporary Passes">
        <div class="text-sm text-slate-500 mb-4">
            Showing all passes whose validity period has elapsed.
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Applicant</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">End Date</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Days Expired</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4"></th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200 text-sm">
                    @php
                        // Example data (replace with actual database results)
                        $expiredPasses = [
                            (object)['id' => 88, 'applicant' => 'D. Wilson (9988)', 'endDate' => 'Oct 05, 2025', 'daysExpired' => 45, 'status' => 'Inactive'],
                            (object)['id' => 75, 'applicant' => 'E. King (1122)', 'endDate' => 'Sep 01, 2025', 'daysExpired' => 70, 'status' => 'Inactive'],
                        ];
                    @endphp

                    @foreach ($expiredPasses as $pass)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">{{ $pass->id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-slate-700 font-medium">{{ $pass->applicant }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-slate-700">{{ $pass->endDate }}</td>
                            <td class="px-6 py-4 whitespace-nowrap font-bold text-red-600">{{ $pass->daysExpired }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <x-status-badge :status="$pass->status" />
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <a href="{{ route('admin.applications.review', ['application' => $pass->id]) }}" class="text-brand-primary font-medium hover:underline">
                                        View Details
                                    </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </x-card>

</x-dashboard-layout>