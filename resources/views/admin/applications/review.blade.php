{{-- resources/views/admin/application/review.blade.php --}}

<x-dashboard-layout title="Admin Review Application" user="Admin">

    {{-- Dummy data for structure review (replace with actual PHP variables passed by the Controller) --}}
    @php
        // ADMIN CONTEXT: Application data skeleton
        $application = (object)[
            'id' => '346tcvbn563456gf',
            'status' => 'Pending', // Change status here to test Approved/Rejected states
            'studentName' => 'Ronald Richards',
            'studentId' => '4600',
            'passType' => 'Temporary Visitor Access',
            'startDate' => '2025-12-01',
            'endDate' => '2025-12-07',
            'reason' => 'Temporary permit for family visit during holidays.',
            'date_applied' => 'Oct 25, 2025',
            'approver_name' => 'Admin User Smith',
            'pass_key' => 'TEMP-XYZ-123',
            'admin_notes' => 'The duration seems fine, awaiting final review.', // Example of previous internal notes
        ];
        
        $applicationId = $application->id;
        $status = $application->status;
    @endphp

    <div class="max-w-6xl mx-auto space-y-6">

        {{-- Top Header Row --}}
        <div class="flex items-center justify-between pb-4 border-b border-stroke">
            <a href="{{ route('admin.applications.manage') }}" class="text-sm font-semibold text-slate-500 dark:text-slate-300 hover:text-iris flex items-center space-x-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                <span>Back to Applications List</span>
            </a>
            
            {{-- Download Pass button (only if already approved/active) --}}
            @if(in_array(strtolower($status), ['approved', 'active']))
                <x-button type="primary" href="#">
                    Download Generated Pass
                </x-button>
            @endif
        </div>

        <h2 class="text-4xl font-hand text-slate-900 dark:text-white flex items-center gap-4">
            Application Review #{{ $applicationId }}
            <x-status-badge :status="$status" class="ml-4 text-base" />
        </h2>

        {{-- Main Detail Grid (Details + Action Panel) --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            {{-- 1. Application Details (Left/Main Column) --}}
            <div class="lg:col-span-2 space-y-6">
                <x-card header="Applicant Details">
                    <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-6 text-base">
                        <div>
                            <dt class="text-slate-500 dark:text-slate-300">Student Name</dt>
                            <dd class="mt-1 font-semibold text-slate-900 dark:text-white">{{ $application->studentName }}</dd>
                        </div>
                        <div>
                            <dt class="text-slate-500 dark:text-slate-300">Student ID</dt>
                            <dd class="mt-1 text-slate-900 dark:text-white">{{ $application->studentId }}</dd>
                        </div>
                        <div>
                            <dt class="text-slate-500 dark:text-slate-300">Pass Type</dt>
                            <dd class="mt-1 text-slate-900 dark:text-white">{{ $application->passType }}</dd>
                        </div>
                        <div>
                            <dt class="text-slate-500 dark:text-slate-300">Duration</dt>
                            <dd class="mt-1 text-slate-900 dark:text-white">{{ $application->startDate }} to {{ $application->endDate }}</dd>
                        </div>
                        <div>
                            <dt class="text-slate-500 dark:text-slate-300">Date Applied</dt>
                            <dd class="mt-1 text-slate-900 dark:text-white">{{ $application->date_applied }}</dd>
                        </div>
                        @if (in_array($status, ['Approved', 'Rejected']))
                        <div>
                            <dt class="text-slate-500 dark:text-slate-300">Decision By</dt>
                            <dd class="mt-1 text-slate-900 dark:text-white">{{ $application->approver_name }}</dd>
                        </div>
                        @endif
                    </dl>
                </x-card>
                
                <x-card header="Reason Provided by Applicant">
                    <p class="text-slate-700 leading-relaxed dark:text-slate-300">{{ $application->reason }}</p>
                </x-card>

                @if ($application->admin_notes)
                    <x-card header="Internal Admin Notes">
                        <p class="text-slate-700 leading-relaxed dark:text-slate-300 italic">{{ $application->admin_notes }}</p>
                    </x-card>
                @endif
                
                <x-card header="Timeline">
                    @php 
                         $timeline = [
                             ['label' => 'Submitted', 'date' => 'Nov 28, 2025', 'status' => 'completed'],
                             ['label' => 'Awaiting Review', 'date' => 'Nov 29, 2025', 'status' => $status === 'Pending' ? 'current' : 'completed'],
                             ['label' => 'Decision Made', 'date' => $status === 'Pending' ? '—' : 'Nov 30, 2025', 'status' => $status !== 'Pending' ? 'completed' : 'upcoming'],
                             ['label' => 'Archived', 'date' => '—', 'status' => 'upcoming'],
                         ];
                    @endphp
                    <ol class="space-y-4">
                        @foreach($timeline as $step)
                            <li class="flex items-start space-x-4">
                                <span class="w-3 h-3 mt-2 rounded-full {{ $step['status'] === 'completed' ? 'bg-mint' : ($step['status'] === 'current' ? 'bg-iris' : 'bg-stroke') }}"></span>
                                <div>
                                    <p class="text-sm font-semibold text-deep-slate dark:text-white">{{ $step['label'] }}</p>
                                    <p class="text-xs text-warm-gray dark:text-slate-400">{{ $step['date'] }}</p>
                                </div>
                            </li>
                        @endforeach
                    </ol>
                </x-card>
            </div>
            
            {{-- 2. Admin Action Panel (Right Column) --}}
            <div class="lg:col-span-1">
                
                @if ($status === 'Pending')
                    {{-- ADMIN ACTION PANEL: Only shown if Status is Pending --}}
                    <x-card header="Review & Decision" class="h-full bg-yellow-50/50 border-4 border-dashed border-yellow-200">
                        <p class="text-sm text-slate-700 mb-4 font-medium">
                            Make a decision on this application. Use the notes field below to document the rationale.
                        </p>
                        
                        {{-- Form action should use your resource route, e.g., 'application.update' --}}
                        <form action="{{ route('application.update', $applicationId) }}" method="POST">
                            @csrf
                            @method('PUT')
                            
                            {{-- Input for Admin Comment/Notes (Required for rejection, optional for approval) --}}
                            <div class="mb-4">
                                <label for="admin_notes" class="block text-sm font-medium text-slate-700">Admin Notes (Mandatory for rejection)</label>
                                <textarea name="admin_notes" id="admin_notes" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-iris focus:ring-iris sm:text-sm"></textarea>
                            </div>

                            <input type="hidden" name="action_type" id="action_type">

                            {{-- Approve Button (filled primary) --}}
                            <div class="flex flex-col gap-3">
                                <x-button type="primary" class="w-full" onclick="document.getElementById('action_type').value='approve';">
                                    Approve Application
                                </x-button>
                                <x-button type="secondary" class="w-full border border-red-500 hover:bg-red-50" onclick="document.getElementById('action_type').value='reject';">
                                    Reject Application
                                </x-button>
                            </div>
                        </form>
                    </x-card>

                @elseif (in_array($status, ['Approved', 'Active']))
                    {{-- Pass Key View for reference --}}
                    <x-card header="Generated Pass Key" class="h-full bg-mint/50 border-2 border-mint">
                        <div class="flex flex-col items-center justify-center space-y-4">
                            <p class="text-2xl font-extrabold tracking-widest text-mint">{{ $application->pass_key }}</p>
                            <div class="w-full max-w-[200px] aspect-square border border-stroke rounded-2xl flex items-center justify-center text-slate-400">
                                

[Image of QR Code]

                            </div>
                            <p class="text-center text-sm font-medium text-slate-500 dark:text-slate-300">Pass Valid for use.</p>
                        </div>
                    </x-card>
                
                @else
                    {{-- General Message for Rejected/Expired Statuses --}}
                    <x-card header="Decision Finalized" class="h-full">
                        <div class="text-center py-8">
                            <p class="text-lg font-semibold text-red-600">Application {{ $status }}</p>
                            <p class="text-sm text-slate-500 mt-2">The decision was finalized by an administrator.</p>
                        </div>
                    </x-card>
                @endif
                
            </div>
        </div>

    </div>

</x-dashboard-layout>