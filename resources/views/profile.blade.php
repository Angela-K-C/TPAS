{{-- resources/views/profile.blade.php --}}

<x-dashboard-layout title="Profile" :user="$user->name" :logoutRoute="$logoutRoute">
    <div class="max-w-2xl mx-auto py-12">

        {{-- Profile Card --}}
        <div class="bg-white border border-stroke rounded-3xl shadow-xl p-10 space-y-6">

            <h2 class="text-2xl font-hand font-semibold text-slate-900 text-center">
                 {{ ucfirst($type) }} Profile
            </h2>
            <p class="text-sm text-warm-gray text-center">
                Your personal details are shown below. Contact administration to update any information.
            </p>

            {{-- Fields --}}
            <div class="space-y-4">
                <div class="flex flex-col">
                    <label for="full_name" class="text-sm font-medium text-deep-slate mb-1">Full Name</label>
                    <input type="text" id="full_name" value="{{ $user->name }}" disabled
                        class="bg-gray-100 text-gray-700 border border-gray-300 rounded-lg px-4 py-2 font-normal focus:outline-none focus:ring-2 focus:ring-iris" />
                </div>

                  @if($type === 'student')
                    <div class="flex flex-col">
                        <label for="student_id" class="text-sm font-medium text-deep-slate mb-1">Student ID</label>
                        <input type="text" id="student_id" value="{{ $user->admission_number }}" disabled
                            class="bg-gray-100 text-gray-700 border border-gray-300 rounded-lg px-4 py-2 font-normal focus:outline-none focus:ring-2 focus:ring-iris" />
                    </div>

                    <div class="flex flex-col">
                        <label for="program" class="text-sm font-medium text-deep-slate mb-1">Program</label>
                        <input type="text" id="program" value="{{ $user->program ?? 'N/A' }}" disabled
                            class="bg-gray-100 text-gray-700 border border-gray-300 rounded-lg px-4 py-2 font-normal focus:outline-none focus:ring-2 focus:ring-iris" />
                    </div>

                     @elseif($type === 'guest')
                    <div class="flex flex-col">
                        <label for="national_id" class="text-sm font-medium text-deep-slate mb-1">National ID</label>
                        <input type="text" id="national_id" value="{{ $user->national_id }}" disabled
                            class="bg-gray-100 text-gray-700 border border-gray-300 rounded-lg px-4 py-2 font-normal focus:outline-none focus:ring-2 focus:ring-iris" />
                    </div>
                @endif

                <div class="flex flex-col">
                    <label for="email" class="text-sm font-medium text-deep-slate mb-1">Email</label>
                    <input type="email" id="email" value="{{ $user->email }}" disabled
                        class="bg-gray-100 text-gray-700 border border-gray-300 rounded-lg px-4 py-2 font-normal focus:outline-none focus:ring-2 focus:ring-iris" />
                </div>
            </div>

            {{-- Status --}}
            <div class="flex flex-col gap-2 mt-2">
                <label class="text-sm font-medium text-deep-slate">Status</label>
                <x-status-badge status="{{ $user->status ?? 'Active' }}" class="text-mint bg-white font-normal px-4 py-2 rounded-lg shadow-sm" />
            </div>

        </div>

    </div>
</x-dashboard-layout>
