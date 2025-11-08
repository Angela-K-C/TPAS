{{-- resources/views/report/lost-id.blade.php --}}

<x-dashboard-layout title="Report Lost ID">

    <div class="max-w-xl mx-auto">
        
        {{-- The card is styled with a prominent danger border to warn the user --}}
        <x-card header="Report Form" class="border-4 border-brand-danger">
            
            <form action="{{ route('report.lost.id.store') }}" method="POST" class="space-y-6">
                @csrf

                {{-- WARNING BLOCK --}}
                <div class="text-lg font-semibold text-brand-danger mb-4 p-4 rounded-md bg-red-50 border border-brand-danger">
                    WARNING: Reporting your ID as lost will immediately deactivate your current access card. A replacement fee may apply. This action cannot be undone.
                </div>
                
                {{-- Lost Date --}}
                <x-input-field id="lost_date" label="Date Lost" type="date" name="lost_date" />

                {{-- Location Lost --}}
                <x-input-field id="location" label="Location Where ID Was Lost (e.g., Library, Bus Stop)" type="text" name="location" placeholder="Enter specific location" />

                {{-- Confirmation Checkbox (CRITICAL STEP) --}}
                <div class="flex items-start pt-4">
                    <div class="flex items-center h-5">
                        {{-- The checkbox uses the danger color --}}
                        <input id="confirm_report" name="confirm_report" type="checkbox" required
                               class="focus:ring-brand-danger h-4 w-4 text-brand-danger border-gray-300 rounded">
                    </div>
                    <div class="ml-3 text-sm">
                        <label for="confirm_report" class="font-medium text-brand-text">
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