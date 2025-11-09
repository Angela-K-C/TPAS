{{-- resources/views/admin/applications/manage.blade.php --}}

<x-dashboard-layout title="Manage Applications" user="Admin">

    {{-- --- Back to Dashboard and Status Filters --- --}}
    <div class="flex items-center justify-between mb-6 border-b pb-4">
        <a href="{{ route('admin.dashboard') }}" class="text-sm font-medium text-slate-500 hover:text-brand-primary flex items-center space-x-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            <span>Back to Dashboard</span>
        </a>

        {{-- Simple Status Filter Tabs --}}
        <div class="hidden sm:flex space-x-2 p-1 bg-gray-100 rounded-lg">
            @php
                // Get the current status filter from the URL, defaulting to 'Pending' if none is set
                $currentFilter = request('status', 'Pending'); 
                $filters = ['Pending', 'Approved', 'Rejected', 'All'];
            @endphp
            
            @foreach ($filters as $filter)
                {{-- Link: Refreshes the page with the new status query parameter --}}
                <a href="{{ route('admin.applications.manage', ['status' => $filter]) }}" 
                   class="px-3 py-1 text-sm font-medium rounded-md transition duration-150 ease-in-out 
                          @if ($currentFilter === $filter) bg-white shadow-sm text-brand-primary 
                          @else text-slate-500 hover:bg-gray-200 @endif">
                    {{ $filter }}
                </a>
            @endforeach
        </div>
    </div>
    
    {{-- --- Applications Table --- --}}
    <x-card header="Applications List ({{ $currentFilter }})">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Applicant</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Submitted</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Duration</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4"></th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200 text-sm">
                    @php
                        // NOTE: In a real application, this data would be dynamically filtered by the Controller
                        // based on the $currentFilter variable (e.g., TemporaryPass::where('status', $currentFilter)->get())
                        
                        // Example: Only show records matching the filter
                        $allApplications = [
                            (object)['id' => 101, 'applicant' => 'A. Richards (2798)', 'type' => 'ID Lost', 'submitted' => '2 days ago', 'status' => 'Pending'],
                            (object)['id' => 102, 'applicant' => 'B. Johnson (4600)', 'type' => 'Visitor', 'submitted' => '5 days ago', 'status' => 'Approved'],
                            (object)['id' => 103, 'applicant' => 'C. Smith (1029)', 'type' => 'Temporary Access', 'submitted' => '1 week ago', 'status' => 'Rejected'],
                            (object)['id' => 104, 'applicant' => 'D. Jones (1111)', 'type' => 'ID Lost', 'submitted' => '1 day ago', 'status' => 'Pending'],
                        ];

                        $applications = collect($allApplications)->filter(function ($app) use ($currentFilter) {
                            if ($currentFilter === 'All') return true;
                            return $app->status === $currentFilter;
                        });
                    @endphp

                    @foreach ($applications as $app)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">{{ $app->id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-slate-700 font-medium">{{ $app->applicant }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-slate-700">{{ $app->type }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-slate-700">{{ $app->submitted }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-slate-700">Dec 15 - Dec 22</td> {{-- Using a placeholder duration for simplicity --}}
                            <td class="px-6 py-4 whitespace-nowrap">
                                <x-status-badge :status="$app->status" />
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <a href="{{ route('admin.applications.review', ['application' => $app->id]) }}" class="text-brand-primary font-medium hover:underline">
                                        View
                                    </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </x-card>

    {{-- Placeholder for Pagination (optional) --}}
    <div class="mt-4">
        {{-- {{ $applications->links() }} --}}
    </div>

</x-dashboard-layout>