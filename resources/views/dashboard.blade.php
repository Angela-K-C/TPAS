{{-- resources/views/dashboard.blade.php --}}

<x-dashboard-layout title="Dashboard">

    {{-- --- Action Buttons --- --}}
    <div class="flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-4 mb-8 justify-end">
        {{-- Apply for a New Pass Button --}}
        <x-button type="primary" href="{{ route('application.create') }}" class="order-2 sm:order-1">
            Apply for a Temporary Pass
        </x-button>

        {{-- Report Lost ID Button --}}
        <x-button type="danger" href="{{ route('report.lost.id') }}" class="order-1 sm:order-2">
            Report Lost ID
        </x-button>
    </div>

    {{-- Application History Table --}}
    <x-card header="My Pass Applications">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-brand-muted uppercase tracking-wider">
                            #
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-brand-muted uppercase tracking-wider">
                            Application ID
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-brand-muted uppercase tracking-wider">
                            Admin. no
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-brand-muted uppercase tracking-wider">
                            Duration
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-brand-muted uppercase tracking-wider">
                            Status
                        </th>
                        <th scope="col" class="relative px-6 py-3">
                            <span class="sr-only">View</span>
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    {{-- Loop through applications here  --}}
                    @php
                        // Replace this
                        $applications = [
                            (object)['id' => 1, 'app_id' => '348tcvbn563456gf', 'admin_no' => '2798', 'duration' => '02/07/1971 - 02/07/1971', 'status' => 'Active'],
                            (object)['id' => 2, 'app_id' => 'Ronald Richards', 'admin_no' => '4600', 'duration' => '02/07/1971 - 02/07/1971', 'status' => 'Inactive'],
                        ];
                    @endphp
                    
                    @foreach ($applications as $app)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $app->id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-brand-text">{{ $app->app_id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-brand-text">{{ $app->admin_no }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-brand-text">{{ $app->duration }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <x-status-badge :status="$app->status" />
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                {{-- Link to the application detail view --}}
                                <a href="{{ route('application.show', ['application' => $app->app_id]) }}" class="text-brand-primary hover:text-indigo-900">
                                    View Detail
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </x-card>

</x-dashboard-layout>