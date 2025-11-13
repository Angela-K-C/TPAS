{{-- resources/views/report/lost-id.blade.php --}}

<x-dashboard-layout title="Report Lost ID">

    <div class="max-w-3xl mx-auto space-y-6">
        <p class="text-slate-500">
            Quickly report a lost ID to deactivate the existing card and request a replacement.
        </p>
        @if (session('status'))
            <div class="wire-card border-l-4 border-mint bg-mint/10 p-5">
                <p class="text-sm text-deep-slate">{{ session('status') }}</p>
            </div>
        @endif
        
        {{-- The card is styled with a prominent danger border to warn the user --}}
        <x-card header="Report Form" class="border-4 border-red-200">
            
            <form action="{{ route('report.lost.id.store') }}" method="POST" class="space-y-6">
                @csrf

                {{-- WARNING BLOCK --}}
                <div class="text-lg font-semibold text-red-600 mb-4 p-4 rounded-2xl bg-red-50 border border-red-200">
                    WARNING: Reporting your ID as lost will immediately deactivate your current access card. A replacement fee may apply. This action cannot be undone.
                </div>
                
                {{-- Lost Date --}}
                <x-input-field id="lost_date" label="Date Lost" type="date" name="lost_date" helper="Select the last day you recall having the ID." />

                {{-- Location Lost --}}
                <x-input-field id="location" label="Location Where ID Was Lost (e.g., Library, Bus Stop)" type="text" name="location" placeholder="Enter specific location" helper="Mention building + room if possible." />

                {{-- Confirmation Checkbox (CRITICAL STEP) --}}
                <div class="flex items-start pt-4">
                    <div class="flex items-center h-5">
                        {{-- The checkbox uses the danger color --}}
                        <input id="confirm_report" name="confirm_report" type="checkbox" required
                               class="h-5 w-5 rounded-md border-2 border-red-400 text-red-500 focus:ring-red-400">
                    </div>
                    <div class="ml-3 text-sm">
                        <label for="confirm_report" class="font-medium text-slate-700">
                            I understand and confirm that I wish to permanently report my ID as lost.
                        </label>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="flex justify-end space-x-4 pt-6">
                    {{-- Cancel Button --}}
                    <x-button type="secondary" href="{{ route('dashboard') }}">
                        Cancel
                    </x-button>
                    
                    {{-- Primary action for this screen must use the danger button style --}}
                    <x-button type="danger">
                        Confirm and Report Lost ID
                    </x-button>
                </div>
                
            </form>
            
        </x-card>
    </div>

</x-dashboard-layout>
