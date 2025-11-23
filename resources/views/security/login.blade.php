@extends('layouts.app', ['title' => 'TPAS Security Desk · Sign In', 'showFooter' => false])

@section('content')
<div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 grid gap-8 lg:grid-cols-[1.05fr_0.95fr] items-start">
        <x-card class="bg-white border border-stroke shadow-sm rounded-2xl">
            <p class="text-xs uppercase tracking-[0.35em] text-iris">Security Portal</p>
            <h1 class="mt-3 text-3xl font-semibold text-slate-900">Gate access control</h1>
            <p class="mt-3 text-slate-600 leading-relaxed">
                Guards use this console to verify temporary passes issued by TPAS. Sign in with your station credentials
                to start scanning QR codes and resolving visitor questions in real time.
            </p>
            <dl class="mt-8 grid gap-4 sm:grid-cols-2 text-sm text-slate-700">
                <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                    <dt class="text-[0.65rem] uppercase tracking-wide text-slate-500">Live status</dt>
                    <dd class="mt-3 flex items-center gap-3 font-semibold text-slate-900">
                        <span class="h-3 w-3 rounded-full bg-green-500 animate-pulse"></span>
                        Connected to AMS
                    </dd>
                </div>
                <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                    <dt class="text-[0.65rem] uppercase tracking-wide text-slate-500">Command centre</dt>
                    <dd class="mt-3 font-medium leading-relaxed text-slate-800">
                        Desk monitors entries round the clock.
                    </dd>
                </div>
            </dl>
            <div class="mt-8 grid gap-6 sm:grid-cols-3 text-center text-xs uppercase tracking-[0.3em] text-slate-500">
                <div class="rounded-xl border border-slate-200 bg-slate-50 py-4">
                    <p class="text-2xl font-semibold tracking-normal text-slate-900">24/7</p>
                    <span>coverage</span>
                </div>
                <div class="rounded-xl border border-slate-200 bg-slate-50 py-4">
                    <p class="text-2xl font-semibold tracking-normal text-slate-900">120+</p>
                    <span>passes/day</span>
                </div>
                <div class="rounded-xl border border-slate-200 bg-slate-50 py-4">
                    <p class="text-2xl font-semibold tracking-normal text-slate-900">4</p>
                    <span>gate nodes</span>
                </div>
            </div>
        </x-card>

        <x-card class="bg-white border border-stroke shadow-sm rounded-2xl">
            <div class="space-y-1 text-center mb-6">
                <p class="text-xs uppercase tracking-[0.35em] text-iris">Secure Sign In</p>
                <h2 class="text-2xl font-semibold text-slate-900">Log in to verify passes</h2>
                <p class="text-sm text-slate-600">Audit events logged automatically</p>
            </div>

            @if ($errors->any())
                <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700 mb-6">
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('security.login.submit') }}" method="POST" class="space-y-5">
                @csrf
                <div>
                    <label for="email" class="text-sm font-semibold text-slate-800">Station email</label>
                    <input type="email" id="email" name="email" required autofocus value="{{ old('email') }}"
                           class="mt-2 w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-base text-slate-900 placeholder:text-slate-400 focus:border-iris focus:ring-2 focus:ring-iris/30 transition">
                </div>
                <div>
                    <label for="password" class="text-sm font-semibold text-slate-800">Password</label>
                    <input type="password" id="password" name="password" required
                           class="mt-2 w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-base text-slate-900 placeholder:text-slate-400 focus:border-iris focus:ring-2 focus:ring-iris/30 transition">
                </div>
                <label for="remember" class="flex items-center gap-3 text-sm text-slate-700">
                    <input type="checkbox" id="remember" name="remember" class="rounded border-slate-300 text-iris focus:ring-iris">
                    Remember this device
                </label>
                <button type="submit"
                        class="w-full rounded-xl bg-iris px-4 py-3 text-base font-semibold text-white shadow-sm hover:bg-indigo-600 focus:ring-2 focus:ring-iris/30 transition">
                    Access portal
                </button>
            </form>
            <p class="mt-6 text-center text-xs text-slate-500">
                Trouble signing in? Contact the AMS administrator to reset your guard credentials.
            </p>
        </x-card>
    </div>
</div>
@endsection
