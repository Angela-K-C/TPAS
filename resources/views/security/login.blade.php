@extends('layouts.app', ['title' => 'TPAS Security Desk · Sign In', 'showFooter' => false])

@section('content')
<section class="min-h-screen bg-gradient-to-br from-[#030712] via-[#0b1120] to-[#151b3a] py-16">
    <div class="max-w-6xl mx-auto px-4 grid gap-10 lg:grid-cols-[1.1fr_0.9fr] items-stretch text-white">
        <div class="rounded-3xl border border-white/10 bg-white/5 backdrop-blur p-10 shadow-[0_60px_120px_rgba(0,0,0,0.55)] relative overflow-hidden">
            <div class="absolute -top-12 -right-16 h-48 w-48 rounded-full bg-iris/40 blur-3xl opacity-70"></div>
            <div class="absolute -bottom-16 -left-10 h-40 w-40 rounded-full bg-mint/30 blur-3xl opacity-60"></div>
            <p class="text-xs uppercase tracking-[0.35em] text-mint/70">Security Portal</p>
            <h1 class="mt-3 text-3xl font-semibold">Gate Access Control</h1>
            <p class="mt-3 text-white/70 leading-relaxed">
                Guards use this console to verify temporary passes issued by TPAS. Sign in with your station
                credentials to start scanning QR codes and resolving visitor questions in real time.
            </p>
            <dl class="mt-8 grid gap-4 sm:grid-cols-2 text-sm text-white/80">
                <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                    <dt class="text-[0.65rem] uppercase tracking-wide text-white/50">Live status</dt>
                    <dd class="mt-3 flex items-center gap-3 font-semibold">
                        <span class="h-3 w-3 rounded-full bg-mint animate-pulse shadow-[0_0_10px_rgba(82,224,196,0.7)]"></span>
                        Connected to AMS
                    </dd>
                </div>
                <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                    <dt class="text-[0.65rem] uppercase tracking-wide text-white/50">Command centre</dt>
                    <dd class="mt-3 font-medium leading-relaxed">
                        Desk monitors entries round the clock.
                    </dd>
                </div>
            </dl>
            <div class="mt-8 grid gap-6 sm:grid-cols-3 text-center text-xs uppercase tracking-[0.3em] text-white/50">
                <div class="rounded-2xl border border-white/10 bg-white/5 py-4">
                    <p class="text-2xl font-semibold tracking-normal text-white">24/7</p>
                    <span>coverage</span>
                </div>
                <div class="rounded-2xl border border-white/10 bg-white/5 py-4">
                    <p class="text-2xl font-semibold tracking-normal text-white">120+</p>
                    <span>passes/day</span>
                </div>
                <div class="rounded-2xl border border-white/10 bg-white/5 py-4">
                    <p class="text-2xl font-semibold tracking-normal text-white">4</p>
                    <span>gate nodes</span>
                </div>
            </div>
        </div>

        <div class="rounded-3xl border border-white/10 bg-[#050915]/90 backdrop-blur-xl p-10 shadow-[0_50px_120px_rgba(0,0,0,0.65)] flex flex-col">
            <div class="space-y-1 text-center mb-8">
                <p class="text-xs uppercase tracking-[0.35em] text-mint/70">Secure Sign In</p>
                <h2 class="text-2xl font-semibold">Log in to verify passes</h2>
                <p class="text-sm text-white/50">Dual-factor enforced · Audit events logged automatically</p>
            </div>

            @if ($errors->any())
                <div class="rounded-2xl border border-red-400/50 bg-red-500/20 px-4 py-3 text-sm text-red-200 mb-6">
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('security.login.submit') }}" method="POST" class="space-y-5">
                @csrf
                <div>
                    <label for="email" class="text-sm font-semibold text-white/80">Station email</label>
                    <input type="email" id="email" name="email" required autofocus value="{{ old('email') }}"
                           class="mt-2 w-full rounded-2xl border border-white/10 bg-white/10 px-4 py-3 text-base text-white placeholder:text-white/40 focus:border-mint focus:ring-2 focus:ring-mint/30 transition">
                </div>
                <div>
                    <label for="password" class="text-sm font-semibold text-white/80">Password</label>
                    <input type="password" id="password" name="password" required
                           class="mt-2 w-full rounded-2xl border border-white/10 bg-white/10 px-4 py-3 text-base text-white placeholder:text-white/40 focus:border-mint focus:ring-2 focus:ring-mint/30 transition">
                </div>
                <label for="remember" class="flex items-center gap-3 text-sm text-white/70">
                    <input type="checkbox" id="remember" name="remember" class="rounded border-white/20 bg-transparent text-mint focus:ring-mint">
                    Remember this device
                </label>
                <button type="submit"
                        class="w-full rounded-2xl bg-gradient-to-r from-iris to-mint px-4 py-3 text-base font-semibold text-white shadow-[0_20px_40px_rgba(91,97,246,0.35)] transition hover:opacity-90">
                    Access portal
                </button>
            </form>
            <p class="mt-6 text-center text-xs text-white/50">
                Trouble signing in? Contact the AMS administrator to reset your guard credentials.
            </p>
        </div>
    </div>
</section>
@endsection
