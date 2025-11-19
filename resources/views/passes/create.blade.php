{{-- resources/views/passes/create.blade.php --}}

<x-dashboard-layout title="Apply for a Temporary Pass" :user="$userLabel" :logoutRoute="$logoutRoute">
    <div class="max-w-4xl mx-auto space-y-8">
        <p class="text-slate-600">
            Fill in the details below so our security team can review and approve your request. We only ask for what is necessary to keep the campus safe.
        </p>

        @if ($errors->any())
            <div class="wire-card border-l-4 border-red-500 bg-red-50 p-5 space-y-2">
                <p class="text-sm font-semibold text-red-700">Please fix the following issues:</p>
                <ul class="text-sm text-red-600 list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('passes.store') }}" method="POST" class="space-y-8">
            @csrf

            <x-card header="Reason for request">
                <div class="space-y-2">
                    <label for="reason" class="text-sm font-semibold text-slate-700">Select a reason</label>
                    <select id="reason" name="reason" class="wire-input" required>
                        <option value="">-- Select Reason --</option>
                        @foreach ($reasonLabels as $key => $label)
                            <option value="{{ $key }}" @selected(old('reason') === $key)>{{ $label }}</option>
                        @endforeach
                    </select>
                    <p class="text-xs text-warm-gray">This helps us determine the right validity period.</p>
                </div>
            </x-card>

            <x-card header="Visitor information">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label for="visitor_name" class="text-sm font-semibold text-slate-700">Full name</label>
                        <input id="visitor_name" name="visitor_name" type="text" value="{{ old('visitor_name', optional($applicant)->name) }}" class="wire-input" placeholder="e.g. Jane Doe">
                    </div>

                    <div class="space-y-2">
                        <label for="national_id" class="text-sm font-semibold text-slate-700">National ID / Admission No.</label>
                        <input id="national_id" name="national_id" type="text" value="{{ old('national_id') }}" class="wire-input" placeholder="Optional">
                    </div>

                    <div class="space-y-2">
                        <label for="email" class="text-sm font-semibold text-slate-700">Email address</label>
                        <input id="email" name="email" type="email" value="{{ old('email', optional($applicant)->email) }}" class="wire-input" placeholder="you@example.com">
                    </div>

                    <div class="space-y-2">
                        <label for="phone" class="text-sm font-semibold text-slate-700">Phone number</label>
                        <input id="phone" name="phone" type="tel" value="{{ old('phone') }}" class="wire-input" placeholder="+2547...">
                    </div>
                </div>
            </x-card>

            <x-card header="Visit details">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label for="host_name" class="text-sm font-semibold text-slate-700">Host name</label>
                        <input id="host_name" name="host_name" type="text" value="{{ old('host_name') }}" class="wire-input" placeholder="Who are you visiting?">
                    </div>

                    <div class="space-y-2">
                        <label for="host_department" class="text-sm font-semibold text-slate-700">Department</label>
                        <input id="host_department" name="host_department" type="text" value="{{ old('host_department') }}" class="wire-input" placeholder="Admissions, Library, ...">
                    </div>

                    <div class="space-y-2">
                        <label for="pass_type" class="text-sm font-semibold text-slate-700">Pass type (optional)</label>
                        <input id="pass_type" name="pass_type" type="text" value="{{ old('pass_type') }}" class="wire-input" placeholder="Visitor, lost ID, etc.">
                    </div>

                    <div class="space-y-2">
                        <label for="purpose" class="text-sm font-semibold text-slate-700">Purpose of visit</label>
                        <input id="purpose" name="purpose" type="text" value="{{ old('purpose') }}" class="wire-input" placeholder="Short description">
                    </div>

                    <div class="space-y-2">
                        <label for="valid_from" class="text-sm font-semibold text-slate-700">Valid from</label>
                        <input id="valid_from" name="valid_from" type="datetime-local" value="{{ old('valid_from') }}" class="wire-input">
                    </div>

                    <div class="space-y-2">
                        <label for="valid_until" class="text-sm font-semibold text-slate-700">Valid until</label>
                        <input id="valid_until" name="valid_until" type="datetime-local" value="{{ old('valid_until') }}" class="wire-input">
                    </div>

                    <div class="sm:col-span-2 space-y-2">
                        <label for="details" class="text-sm font-semibold text-slate-700">Additional details</label>
                        <textarea id="details" name="details" rows="4" class="wire-input" placeholder="Anything else the guards should know?">{{ old('details') }}</textarea>
                    </div>
                </div>
            </x-card>

            <div class="flex justify-end items-center space-x-4">
                <x-button type="secondary" href="{{ route('passes.index') }}">
                    Cancel
                </x-button>
                <x-button type="primary">
                    Submit application
                </x-button>
            </div>
        </form>
    </div>
</x-dashboard-layout>
