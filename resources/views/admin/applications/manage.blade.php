{{-- resources/views/admin/applications/manage.blade.php --}}

<x-dashboard-layout title="Manage Applications" user="Admin">

    {{-- --- Back to Dashboard and Status Filters --- --}}
    <div class="flex items-center justify-between mb-6 border-b pb-4">
        <a href="{{ route('admin.dashboard') }}" class="text-sm font-medium text-slate-500 hover:text-brand-primary flex items-center space-x-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            <span>Back to Dashboard</span>
        </a>

        {{-- Status Filter Tabs --}}
        <div class="hidden sm:flex space-x-2 p-1 bg-gray-100 rounded-lg">
            @php
                $filters = ['Pending', 'Approved', 'Rejected', 'All'];
            @endphp

            @foreach ($filters as $filter)
                <a href="{{ route('admin.applications.manage', ['status' => $filter]) }}"
                   class="px-3 py-1 text-sm font-medium rounded-md transition duration-150 ease-in-out
                          @if ($currentFilter === $filter) bg-white shadow-sm text-brand-primary
                          @else text-slate-500 hover:bg-gray-200 @endif">
                    {{ $filter }}
                </a>
            @endforeach
        </div>
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

    {{-- --- Applications Table --- --}}
    <x-card header="Applications List ({{ $currentFilter }})">
        <div class="overflow-x-auto">
            @if (session('success'))
                <div class="mb-4 rounded-2xl border border-mint bg-mint/10 px-4 py-3 text-sm text-green-700">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('status'))
                <div class="mb-4 rounded-2xl border border-amber-300 bg-amber-50 px-4 py-3 text-sm text-amber-800">
                    {{ session('status') }}
                </div>
            @endif

            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Applicant</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Submitted</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Duration</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200 text-sm">
                    @forelse ($applications as $app)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">#VST-{{ $app->id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-slate-700 font-medium">
                                {{ class_basename($app->passable_type) }} ({{ $app->passable_id }})
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-slate-700">
                                {{ $app->pass_type ?? class_basename($app->passable_type) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-slate-700">{{ $app->created_at->diffForHumans() }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-slate-700">
                                @if ($app->valid_from && $app->valid_until)
                                    {{ $app->valid_from->format('M d') }} - {{ $app->valid_until->format('M d') }}
                                @else
                                    —
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <x-status-badge :status="$app->status" />
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right space-x-4">
                                <a href="{{ route('admin.applications.review', ['application' => $app->id]) }}"
                                   class="text-brand-primary font-medium hover:underline">
                                    View
                                </a>
                                <form action="{{ route('admin.passes.reset', $app) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="text-iris hover:text-indigo-700 font-semibold text-sm">
                                        Reset
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-4 text-center text-slate-500">No applications found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-card>

</x-dashboard-layout>
