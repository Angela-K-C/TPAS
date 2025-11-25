{{-- resources/views/admin/email-logs.blade.php --}}
@php use Illuminate\Support\Str; @endphp

<x-dashboard-layout title="Email Delivery" user="Admin" :logoutRoute="route('admin.logout')">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
        <div>
            <p class="text-2xl font-semibold text-slate-800">Email Delivery Events</p>
            <p class="text-sm text-slate-500">Capture pass approval/rejection notifications and delivery outcomes.</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.dashboard') }}" class="text-sm text-iris hover:underline">← Back to dashboard</a>
        </div>
    </div>

    <x-card header="Recent emails">
        <form method="get" class="flex flex-col sm:flex-row sm:items-center gap-3 mb-4">
            <label class="text-sm text-slate-600">Status</label>
            <select name="status" class="rounded-md border-gray-300 text-sm" onchange="this.form.submit()">
                @php
                    $statuses = ['' => 'All', 'sent' => 'Sent', 'queued' => 'Queued', 'failed' => 'Failed'];
                @endphp
                @foreach ($statuses as $key => $label)
                    <option value="{{ $key }}" @selected($status === $key)>{{ $label }}</option>
                @endforeach
            </select>
        </form>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Pass</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Recipient</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Subject</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Sent at</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Error</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @php
                        $statusStyles = [
                            'sent' => 'bg-green-100 text-green-700',
                            'queued' => 'bg-amber-100 text-amber-700',
                            'failed' => 'bg-red-100 text-red-700',
                        ];
                    @endphp
                    @forelse ($logs as $log)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-3 whitespace-nowrap">
                                @if ($log->temporary_pass_id)
                                    <a href="{{ route('admin.applications.review', ['application' => $log->temporary_pass_id]) }}" class="text-iris font-medium hover:underline">
                                        #{{ $log->temporary_pass_id }}
                                    </a>
                                @else
                                    <span class="text-slate-500">—</span>
                                @endif
                            </td>
                            <td class="px-6 py-3 whitespace-nowrap font-medium text-slate-800">{{ $log->recipient_email }}</td>
                            <td class="px-6 py-3 whitespace-nowrap text-slate-700">{{ $log->subject }}</td>
                            <td class="px-6 py-3 whitespace-nowrap">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $statusStyles[$log->status] ?? 'bg-slate-100 text-slate-700' }}">
                                    {{ ucfirst($log->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-3 whitespace-nowrap text-slate-600">
                                {{ $log->sent_at ? \Carbon\Carbon::parse($log->sent_at)->format('Y-m-d H:i') : '—' }}
                            </td>
                            <td class="px-6 py-3 whitespace-nowrap text-slate-500 max-w-xs truncate" title="{{ $log->error_message }}">
                                {{ $log->error_message ? Str::limit($log->error_message, 60) : '—' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-slate-500">No email activity yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $logs->withQueryString()->links() }}
        </div>
    </x-card>
</x-dashboard-layout>
