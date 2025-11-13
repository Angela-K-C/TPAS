{{-- resources/views/application/create.blade.php --}}

{{-- Extends the dashboard layout and sets the page title --}}
<x-dashboard-layout title="Apply for New Temporary Pass" :user="auth()->user()->name">


    <div class="max-w-5xl mx-auto space-y-8">
        <p class="text-slate-500">
            Provide the details below to apply for a new temporary pass. All student information is pulled automatically from your profile.
        </p>
        @if (session('status'))
            <div class="wire-card border-l-4 border-mint bg-mint/10 p-5">
                <p class="text-sm text-deep-slate">{{ session('status') }}</p>
            </div>
        @endif
        
        {{-- Form action  --}}
        <form action="{{ route('application.store') }}" method="POST" class="space-y-8">
            @csrf

            {{-- x-card component--}}
            <x-card header="1. Applicant Details">
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    
                    {{-- Student Name --}}
                    <div>
                        <x-input-field id="name" label="Student Name" type="text" value="{{ auth()->user()->name }}" disabled />
                        <p class="mt-2 text-sm text-slate-500">This is pulled from your student profile.</p>
                    </div>

                    {{-- Student ID / Admission Number --}}
                    <div>
                        <x-input-field id="student_id" label="Admission Number" type="text" value="{{ auth()->user()->id }}" disabled />
                    </div>
                </div>
            </x-card>

            {{-- --- 2. Pass Details Card --- --}}
            <x-card header="2. Pass Details">
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    
                    {{-- Pass Type - select) --}}
                    <div>
                        <x-input-field id="pass_type" label="Pass Type" type="select" name="pass_type" helper="Choose the option that best matches your need.">
                            <option value="">Select Pass Type</option>
                            <option value="ID_LOST">ID Lost Replacement</option>
                            <option value="TEMPORARY_VISITOR">Temporary Visitor Access</option>
                            <option value="TEMPORARY_ACCESS">Temporary Access (Other)</option>
                        </x-input-field>
                    </div>

                    {{-- Date From --}}
                    <div>
                        <x-input-field id="date_from" label="Start Date" type="date" name="date_from" helper="Passes can begin as early as tomorrow." />
                    </div>
                    
                    {{-- Date To --}}
                    <div>
                        <x-input-field id="date_to" label="End Date" type="date" name="date_to" helper="Maximum 7 days duration." />
                    </div>

                    {{-- Reason for Application (text area) --}}
                    <div class="sm:col-span-2">
                        <x-input-field id="reason" label="Detailed Reason for Application" type="textarea" name="reason" helper="Share context for security (people involved, locations, etc.)." />
                    </div>
                </div>
            </x-card>

            {{-- --- 3. Action Buttons --- --}}
            <div class="flex justify-end space-x-4 pt-4">
                {{-- Cancel Button --}}
                <x-button type="secondary" href="{{ route('dashboard') }}">
                    Cancel
                </x-button>
                
                {{-- Submit Button --}}
                <x-button type="primary">
                    Apply for a Temporary Pass
                </x-button>
            </div>
            
        </form>
    </div>

</x-dashboard-layout>
