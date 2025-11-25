{{-- resources/views/guest/application-create.blade.php --}}

<x-dashboard-layout title="New Visitor's Pass Application" :user="auth()->user()?->name ?? 'Guest'">


    <div class="max-w-5xl mx-auto space-y-8">
        <p class="text-slate-500">
            Share your identification and visit details so we can prepare a visitor pass that matches the exact schedule from the wireframes.
        </p>
        @if (session('status'))
            <div class="wire-card border-l-4 border-mint bg-mint/10 p-5">
                <p class="text-sm text-deep-slate">{{ session('status') }}</p>
            </div>
        @endif
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

        <form action="{{ route('guest.application.store') }}" method="POST" class="space-y-8" enctype="multipart/form-data">
            @csrf
           
            @if(session('success'))
        <div class="wire-card border-l-4 border-mint bg-mint/10 p-5">
            <p class="text-sm text-deep-slate">{{ session('success') }}</p>
        </div>
    @endif

            {{-- Visitor Details --}}
            <x-card header="1. Visitor Details">
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <x-input-field id="visitor_name" name="visitor_name" label="Visitor Name" type="text" value="{{ $guest->name ?? '' }}" required helper="Exactly as it appears on the identification card." />
                    <x-input-field id="national_id" name="national_id" label="National ID Number" type="text"  value="{{ $guest->national_id ?? '' }}"  helper="Digits only · e.g. 12345678" />
                    <x-input-field id="email" name="email" label="Email Address" type="email"  value="{{ $guest->email ?? ($email ?? '') }}" required helper="We send your QR pass and updates here." />
                    <x-input-field id="phone" name="phone" label="Phone Number" type="tel"  value="{{ $guest->phone ?? '' }}"  helper="Optional but helps our guards reach you if needed." />
                    <div>
                        <label for="profile_photo" class="block text-sm font-medium text-slate-700 mb-1">Profile Photo <span class="text-red-500">*</span></label>
                        <div class="flex items-center space-x-4">
                            <label for="profile_photo" class="inline-flex items-center px-4 py-2 bg-white border border-slate-300 rounded-md shadow-sm text-sm font-medium text-slate-700 hover:bg-slate-50 cursor-pointer focus:outline-none focus:ring-2 focus:ring-indigo-200 focus:ring-opacity-50">
                                <svg class="w-5 h-5 mr-2 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
                                Choose Image
                                <input id="profile_photo" name="profile_photo" type="file" accept="image/*" class="hidden" required />
                            </label>
                            <span id="profile_photo_filename" class="text-slate-500 text-sm"></span>
                        </div>
                        <p class="mt-2 text-sm text-slate-500">Upload a clear photo of yourself (JPG, PNG, max 2MB).</p>
                        <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            const input = document.getElementById('profile_photo');
                            const filename = document.getElementById('profile_photo_filename');
                            if (input) {
                                input.addEventListener('change', function(e) {
                                    if (input.files.length > 0) {
                                        filename.textContent = input.files[0].name;
                                    } else {
                                        filename.textContent = '';
                                    }
                                });
                            }
                        });
                        </script>
                    </div>
                </div>
            </x-card>

            {{-- Visit Details --}}
            <x-card header="2. Visit Details">
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <x-input-field id="host_name" label="Host Name" type="text" name="host_name" helper="Who invited you on campus?" />
                    <x-input-field id="host_department" label="Host Department" type="text" name="host_department" helper="e.g. Admissions, Library, Security" />
                    <x-input-field id="visit_start" label="Visit Start Date" type="date" name="visit_start" helper="You can arrive up to 30 mins before this time." />
                    <x-input-field id="visit_end" label="Visit End Date" type="date" name="visit_end" helper="Passes expire automatically at midnight." />
                    <div class="sm:col-span-2">
                        <x-input-field id="purpose" label="Purpose of Visit" type="textarea" name="purpose" helper="Mention any equipment/luggage you will carry." />
                    </div>
                </div>
            </x-card>

            <div class="flex justify-end space-x-4 pt-4">
                <x-button type="secondary" href="{{ route('guest.dashboard') }}">
                    Cancel
                </x-button>
                <x-button type="primary">
                    Apply for a Visitor's Pass
                </x-button>
            </div>
        </form>
    </div>

</x-dashboard-layout>
