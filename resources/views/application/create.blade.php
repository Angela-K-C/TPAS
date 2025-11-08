{{-- resources/views/application/create.blade.php --}}

{{-- Extends the dashboard layout and sets the page title --}}
<x-dashboard-layout title="Apply for New Temporary Pass">

    <div class="max-w-4xl mx-auto">
        
        {{-- Form action  --}}
        <form action="{{ route('application.store') }}" method="POST" class="space-y-6">
            @csrf

            {{-- x-card component--}}
            <x-card header="1. Applicant Details">
                <div class="grid grid-cols-1 gap-y-6 sm:grid-cols-6 sm:gap-x-6">
                    
                    {{-- Student Name --}}
                    <div class="sm:col-span-3">
                        <x-input-field id="name" label="Student Name" type="text" value="[Student Name]" disabled />
                        <p class="mt-2 text-sm text-brand-muted">This is pulled from your student profile.</p>
                    </div>

                    {{-- Student ID / Admission Number --}}
                    <div class="sm:col-span-3">
                        <x-input-field id="student_id" label="Admission Number" type="text" value="[Student ID/2798]" disabled />
                    </div>
                </div>
            </x-card>

            {{-- --- 2. Pass Details Card --- --}}
            <x-card header="2. Pass Details">
                <div class="grid grid-cols-1 gap-y-6 sm:grid-cols-6 sm:gap-x-6">
                    
                    {{-- Pass Type - select) --}}
                    <div class="sm:col-span-3">
                        <x-input-field id="pass_type" label="Pass Type" type="select" name="pass_type">
                            <option value="">Select Pass Type</option>
                            <option value="ID_LOST">ID Lost Replacement</option>
                            <option value="TEMPORARY_VISITOR">Temporary Visitor Access</option>
                            <option value="TEMPORARY_ACCESS">Temporary Access (Other)</option>
                        </x-input-field>
                    </div>

                    {{-- Date From --}}
                    <div class="sm:col-span-3">
                        <x-input-field id="date_from" label="Start Date" type="date" name="date_from" />
                    </div>
                    
                    {{-- Date To --}}
                    <div class="sm:col-span-3">
                        <x-input-field id="date_to" label="End Date" type="date" name="date_to" />
                        <p class="mt-2 text-sm text-brand-muted">Maximum 7 days duration.</p>
                    </div>

                    {{-- Reason for Application (text area) --}}
                    <div class="sm:col-span-6">
                        <x-input-field id="reason" label="Detailed Reason for Application" type="textarea" name="reason" />
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