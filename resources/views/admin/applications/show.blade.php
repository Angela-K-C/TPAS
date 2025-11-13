{{-- resources/views/application/show.blade.php --}}
{{-- please ignore this section!!!!! --}}
<x-dashboard-layout title="Application Detail">


    @php
        // Placeholder data to simulate an object passed from the controller (e.g., $application)
        $application = (object)[
            'id' => '346tcvbn563456gf',
            'status' => 'Pending', // Key to Admin logic: Pending, Approved, Rejected, Active, Expired
            'studentName' => 'Ronald Richards',
            'studentId' => '4600',
            'passType' => 'Temporary Visitor Access',
            'startDate' => '2025-12-01',
            'endDate' => '2025-12-07',
            'reason' => 'Temporary permit for family visit during holidays.',
            'date_applied' => 'Oct 25, 2025',
            'approval_date' => 'Oct 26, 2025',
            'pass_key' => 'TEMP-XYZ-123',
            'approver_name' => 'Admin User Smith', // Added for Approved/Rejected state
            'reviewed_at' => '2025-10-26 10:00:00', // Added for Approved/Rejected state
        ];

        // Role check (Replace with Laravel Auth check: Auth::guard('admin')->check() or similar logic)
        $is_admin = true; 

        // Variables for cleaner use
        $applicationId = $application->id;
        $status = $application->status; 
    @endphp

    <div class="max-w-6xl mx-auto space-y-6">

        {{-- Top Header Row --}}
        <div class="flex items-center justify-between pb-4 border-b border-stroke">
            
            {{-- Back Button: Links to Admin Manage page if admin, otherwise Dashboard --}}
            @if ($is_admin)
                <a href="{{ route('admin.applications.manage') }}" class="text-sm font-semibold text-slate-500 dark:text-slate-300 hover:text-slate-900 dark:text-white flex items-center space-x-2">
                    <span class="wire-icon-button w-8 h-8"> ← </span>
                    <span>Back to Applications List</span>
                </a>
            @else
                <a href="{{ route('dashboard') }}" class="text-sm font-semibold text-slate-500 dark:text-slate-300 hover:text-slate-900 dark:text-white flex items-center space-x-2">
                    <span class="wire-icon-button w-8 h-8"> ← </span>
                    <span>Back to Dashboard</span>
                </a>
            @endif
            
            {{-- Download Button (Only visible to Applicant if Active/Approved) --}}
            @if(!$is_admin && strtolower($status) === 'approved')
                <x-button type="primary" href="#">
                    Download Temporary Pass
                </x-button>
            @endif
        </div>

        <h2 class="text-4xl font-hand text-slate-900 dark:text-white flex items-center gap-4">
            Application #{{ $applicationId }}
            <x-status-badge :status="$status" class="ml-4 text-base" />
        </h2>

        {{-- Main Detail Grid (Details + Action/QR Code) --}}
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
                                <dt class="text-slate-500 dark:text-slate-300">Reviewed Date</dt>
                                <dd class="mt-1 text-slate-900 dark:text-white">{{ \Carbon\Carbon::parse($application->reviewed_at)->format('M d, Y') }}</dd>
                            </div>
                        @endif
                    </dl>
                </x-card>
                
                <x-card header="Reason Provided">
                    <p class="text-slate-700 leading-relaxed dark:text-slate-300">{{ $application->reason }}</p>
                </x-card>

                @if (in_array($status, ['Approved', 'Rejected']))
                <x-card header="Reviewer Notes">
                    <dl class="text-base">
                        <div>
                            <dt class="text-slate-500 dark:text-slate-300">Decision By:</dt>
                            <dd class="mt-1 font-semibold text-slate-900 dark:text-white">{{ $application->approver_name }}</dd>
                        </div>
                        <div class="mt-4">
                             <dt class="text-slate-500 dark:text-slate-300">Comment:</dt>
                             <dd class="mt-1 text-slate-900 dark:text-white italic">
                                @if ($status === 'Approved')
                                    "Application is approved. Access key generated."
                                @elseif ($status === 'Rejected')
                                    "The temporary pass duration exceeds the allowed limit."
                                @endif
                             </dd>
                        </div>
                    </dl>
                </x-card>
                @endif
                
                {{-- Timeline remains for all users to see status progression --}}
                <x-card header="Timeline">
                    <ol class="space-y-4">
                        @php 
                             // Dummy timeline update for clarity
                             $timeline = [
                                 ['label' => 'Submitted', 'date' => 'Nov 28, 2025', 'status' => 'completed'],
                                 ['label' => 'Reviewed by Admin', 'date' => 'Nov 29, 2025', 'status' => $status !== 'Pending' ? 'completed' : 'current'],
                                 ['label' => $status === 'Approved' ? 'Pass Generated' : ($status === 'Rejected' ? 'Application Rejected' : 'Processing'), 'date' => $status !== 'Pending' ? 'Nov 30, 2025' : '—', 'status' => $status === 'Approved' || $status === 'Rejected' ? 'completed' : 'upcoming'],
                                 ['label' => 'Archived', 'date' => '—', 'status' => 'upcoming'],
                             ];
                         @endphp
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
            
            {{-- 2. QR Code / Admin Action Panel (Right Column) --}}
            <div class="lg:col-span-1">
                
                @if ($is_admin && $status === 'Pending')
                    {{-- ADMIN ACTION PANEL: Only visible to Admin AND if Status is Pending --}}
                    <x-card header="Review & Decision" class="h-full bg-yellow-50/50 border-4 border-dashed border-yellow-200">
                        <p class="text-sm text-slate-700 mb-4 font-medium">
                            Make a decision on this application. This action cannot be reversed.
                        </p>
                        
                        {{-- The Action Form targets the update method on your controller --}}
                        <form action="{{ route('application.update', $applicationId) }}" method="POST">
                            @csrf
                            @method('PUT')
                            
                            {{-- Input for Admin Comment/Notes (Optional) --}}
                            <div class="mb-4">
                                <label for="admin_notes" class="block text-sm font-medium text-slate-700">Admin Notes (Optional)</label>
                                <textarea name="admin_notes" id="admin_notes" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-iris focus:ring-iris sm:text-sm"></textarea>
                            </div>

                            {{-- Hidden field to signal the action type --}}
                            <input type="hidden" name="action_type" id="action_type">

                            {{-- Approve Button --}}
                            <x-button type="primary" class="w-full mb-3" onclick="document.getElementById('action_type').value='approve';">
                                👍 Approve Application
                            </x-button>

                            {{-- Reject Button --}}
                            <x-button type="danger" class="w-full" onclick="document.getElementById('action_type').value='reject';">
                                ❌ Reject Application
                            </x-button>
                        </form>
                    </x-card>

                @elseif (in_array($status, ['Approved', 'Active']))
                    {{-- QR Code / Pass Key View: Visible to Applicant (Active) and Admin (Approved) --}}
                    <x-card header="Temporary Pass Key" class="h-full bg-mint/50 border-2 border-mint">
                        <div class="flex flex-col items-center justify-center space-y-4">
                            <p class="text-2xl font-extrabold tracking-widest text-mint">{{ $application->pass_key }}</p>
                            <div class="w-full max-w-[200px] aspect-square border border-stroke rounded-2xl flex items-center justify-center text-slate-400">
                                

[Image of QR Code]

                            </div>
                            <p class="text-center text-sm font-medium text-slate-500 dark:text-slate-300">Scan for Validation</p>
                        </div>
                    </x-card>
                
                @else
                    {{-- General Message for Rejected/Expired/Inactive Statuses --}}
                    <x-card header="Review Status">
                        <div class="text-center py-8">
                             @if ($status === 'Rejected')
                                <p class="text-lg font-semibold text-red-600">Application Rejected</p>
                                <p class="text-sm text-slate-500 mt-2">The request was denied by the Admin.</p>
                             @else
                                <p class="text-lg font-semibold text-slate-600">Pass Inactive</p>
                                <p class="text-sm text-slate-500 mt-2">This pass is no longer valid or has expired.</p>
                             @endif
                        </div>
                    </x-card>
                @endif
                
            </div>
        </div>

    </div>

</x-dashboard-layout>