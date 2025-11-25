{{-- resources/views/help.blade.php --}}

@extends('layouts.app', ['title' => 'TPAS Help & FAQ'])

@section('content')
<div class="bg-gray-50 py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
        <header class="space-y-2">
            <p class="text-xs font-semibold tracking-widest text-iris uppercase">Help Centre</p>
            <h1 class="text-3xl sm:text-4xl font-hand text-slate-900">
                Temporary Pass Application System (TPAS) – Help & FAQ
            </h1>
            <p class="text-sm text-slate-600 max-w-2xl">
                Use this page to quickly understand how TPAS works, what the different portals do,
                and how to resolve the most common issues.
            </p>
        </header>

        <section class="grid gap-6 md:grid-cols-2">
            <x-card header="Getting Started">
                <ul class="space-y-3 text-sm text-slate-700">
                    <li><span class="font-semibold">Students:</span> Sign in via the <span class="font-mono">Student</span> option on the login screen using your university email.</li>
                    <li><span class="font-semibold">Guests:</span> Use the <span class="font-mono">Guest</span> login and enter your email address to receive a temporary profile.</li>
                    <li><span class="font-semibold">Admins & Security:</span> Use your assigned credentials on the respective login screens.</li>
                </ul>
            </x-card>

            <x-card header="Applying for a Temporary Pass">
                <ul class="space-y-3 text-sm text-slate-700">
                    <li>Students apply from <span class="font-mono">Dashboard → Apply for Temporary Pass</span>.</li>
                    <li>Guests apply from the <span class="font-mono">Guest Dashboard</span> after logging in.</li>
                    <li>Fill in the required fields (reason, visit dates, host details) then submit the form.</li>
                    <li>You will see the application under “My Applications” together with its current status.</li>
                </ul>
            </x-card>
        </section>

        <section class="grid gap-6 md:grid-cols-2">
            <x-card header="Lost or Damaged IDs">
                <ul class="space-y-3 text-sm text-slate-700">
                    <li>To report a lost ID, go to <span class="font-mono">Dashboard → Report Lost ID</span>.</li>
                    <li>Provide the last day you remember having the card and where you think it was lost.</li>
                    <li>Submitting the form will log a report for Security and may deactivate your current card.</li>
                </ul>
            </x-card>

            <x-card header="Application Status & QR Codes">
                <ul class="space-y-3 text-sm text-slate-700">
                    <li>When a pass is approved, you will receive an email with your QR code and a PDF copy.</li>
                    <li>You can also open any approved pass from your dashboard and download/print the QR code again.</li>
                    <li>Security staff can verify your QR at the gate using the Security Desk portal.</li>
                </ul>
            </x-card>
        </section>

        <section class="grid gap-6 md:grid-cols-2">
            <x-card header="Validation Errors & Common Issues">
                <ul class="space-y-3 text-sm text-slate-700">
                    <li><span class="font-semibold">Red error messages</span> under form fields mean some required information is missing or invalid.</li>
                    <li>If the system says you already have an active application, wait for it to expire or visit the security office for manual assistance.</li>
                    <li>After correcting the highlighted fields, resubmit the form to continue.</li>
                </ul>
            </x-card>

            <x-card header="Who to Contact for Help">
                <ul class="space-y-3 text-sm text-slate-700">
                    <li>For issues with your pass (incorrect dates, wrong details, urgent changes), contact the security office.</li>
                    <li>For login problems or system errors that persist, report the issue to the TPAS administrator.</li>
                    <li>When reporting problems, include your Application ID (e.g. <span class="font-mono">TPAS-123</span>) if available.</li>
                </ul>
            </x-card>
        </section>
    </div>
</div>
@endsection

