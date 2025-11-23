@extends('layouts.app', ['title' => 'TPAS Security Desk · Verify Pass', 'showFooter' => false])

@section('content')
<div class="min-h-screen bg-gray-50">
    <x-security-navbar :userLabel="($guard?->name ?? 'Security Desk')" :logoutRoute="route('security.logout')" />

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-6">
        <div class="bg-white rounded-2xl border border-stroke shadow-sm p-6 flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div class="space-y-1">
                <p class="text-xs uppercase tracking-[0.35em] text-iris">Security Portal</p>
                <h1 class="text-2xl font-semibold text-slate-900">Verify a temporary pass</h1>
                <p class="text-sm text-slate-600">Paste the token printed under the QR code or launch the live scanner. All lookups are audited.</p>
            </div>
            <div class="flex flex-wrap items-center gap-2 text-xs text-slate-600">
                <span class="inline-flex items-center gap-2 rounded-full bg-green-50 px-3 py-1 font-semibold text-green-700">
                    <span class="h-2 w-2 rounded-full bg-green-500 animate-pulse"></span>
                    Live link
                </span>
                <span class="inline-flex items-center gap-2 rounded-full bg-slate-100 px-3 py-1 font-semibold text-slate-700">
                    QR + manual lookup
                </span>
            </div>
        </div>

        <div class="grid gap-6 lg:grid-cols-5">
            <section class="lg:col-span-3">
                <x-card class="shadow-sm border border-stroke bg-white" header="Lookup console">
                    <div class="space-y-4">
                        <label for="token" class="text-sm font-medium text-slate-700">QR token</label>
                        <div class="flex flex-col gap-3 sm:flex-row">
                            <input type="text" id="token" name="token" placeholder="Paste or scan token..."
                                   class="flex-1 rounded-2xl border border-slate-200 bg-white px-4 py-4 text-base text-slate-900 placeholder:text-slate-400 focus:border-iris focus:ring-2 focus:ring-iris/30 transition shadow-xs" />
                            <button id="lookupBtn"
                                    class="rounded-2xl bg-iris px-5 py-4 font-semibold text-white text-base shadow-sm hover:bg-indigo-600 focus:ring-2 focus:ring-iris/30 w-full sm:w-auto">
                                Lookup
                            </button>
                            <button id="scanToggle"
                                    class="rounded-2xl border border-slate-200 px-5 py-4 font-semibold text-slate-700 text-base hover:border-iris hover:text-iris focus:ring-2 focus:ring-iris/20 w-full sm:w-auto">
                                Scan QR
                            </button>
                        </div>
                        <div id="scannerContainer" class="hidden rounded-2xl border border-dashed border-slate-200 bg-slate-50 p-4 space-y-3">
                            <video id="qrVideo" class="w-full rounded-xl bg-black/70" playsinline></video>
                            <p class="text-xs text-slate-600">Point the device camera at the QR label. We’ll auto-fill the token.</p>
                            <button id="stopScan" class="inline-flex items-center justify-center gap-2 rounded-xl bg-slate-900 px-4 py-3 text-sm font-semibold text-white hover:bg-slate-800 w-full sm:w-auto">
                                Stop scanning
                            </button>
                        </div>
                    </div>
                </x-card>
            </section>

            <section id="resultCard" class="lg:col-span-2 hidden">
                <x-card class="shadow-sm border border-stroke bg-white">
                    <div id="resultHeader" class="flex items-center gap-3">
                        <div id="statusDot" class="h-3 w-3 rounded-full bg-slate-300"></div>
                        <p id="statusText" class="text-lg font-semibold text-slate-900">Awaiting lookup…</p>
                    </div>
                    <p id="expiryCountdown" class="mt-2 text-sm text-slate-600"></p>
                    <dl class="mt-6 grid gap-4 text-sm text-slate-700">
                        <div class="flex gap-4 items-center">
                            <div class="relative h-24 w-24 overflow-hidden rounded-xl border border-slate-200 bg-slate-100">
                                <img id="holderPhoto" class="hidden h-full w-full object-cover" alt="Pass holder photo">
                                <div id="holderPhotoFallback" class="flex h-full w-full items-center justify-center text-[0.65rem] font-semibold uppercase tracking-wide text-slate-500">
                                    No photo
                                </div>
                            </div>
                            <div>
                                <dt class="text-xs uppercase tracking-wide text-slate-500">Holder</dt>
                                <dd class="mt-2 text-base font-semibold text-slate-900" id="holderName">—</dd>
                                <p class="text-sm text-slate-600" id="holderEmail">—</p>
                            </div>
                        </div>
                        <div class="grid gap-4 sm:grid-cols-2">
                            <div>
                                <dt class="text-xs uppercase tracking-wide text-slate-500">Pass reference</dt>
                                <dd class="mt-2 font-semibold text-slate-900" id="passReference">—</dd>
                            </div>
                            <div>
                                <dt class="text-xs uppercase tracking-wide text-slate-500">Pass type</dt>
                                <dd class="mt-2 font-semibold text-slate-900" id="passType">—</dd>
                            </div>
                            <div>
                                <dt class="text-xs uppercase tracking-wide text-slate-500">Status</dt>
                                <dd class="mt-2 font-semibold text-slate-900" id="statusValue">—</dd>
                            </div>
                            <div>
                                <dt class="text-xs uppercase tracking-wide text-slate-500">Reason</dt>
                                <dd class="mt-2 font-semibold text-slate-900" id="reasonValue">—</dd>
                            </div>
                            <div>
                                <dt class="text-xs uppercase tracking-wide text-slate-500">Valid from</dt>
                                <dd class="mt-2 text-slate-700" id="validFrom">—</dd>
                            </div>
                            <div>
                                <dt class="text-xs uppercase tracking-wide text-slate-500">Valid until</dt>
                                <dd class="mt-2 text-slate-700" id="validUntil">—</dd>
                            </div>
                        </div>
                    </dl>
                </x-card>
            </section>
        </div>
    </div>
</div>

<script>
    const tokenInput = document.getElementById('token');
    const lookupBtn = document.getElementById('lookupBtn');
    const scanToggle = document.getElementById('scanToggle');
    const scannerContainer = document.getElementById('scannerContainer');
    const qrVideo = document.getElementById('qrVideo');
    const stopScan = document.getElementById('stopScan');
    const resultCard = document.getElementById('resultCard');
    const statusDot = document.getElementById('statusDot');
    const statusText = document.getElementById('statusText');
    const statusValue = document.getElementById('statusValue');
    const holderName = document.getElementById('holderName');
    const holderEmail = document.getElementById('holderEmail');
    const passType = document.getElementById('passType');
    const passReference = document.getElementById('passReference');
    const validFrom = document.getElementById('validFrom');
    const validUntil = document.getElementById('validUntil');
    const expiryCountdown = document.getElementById('expiryCountdown');
    const holderPhoto = document.getElementById('holderPhoto');
    const holderPhotoFallback = document.getElementById('holderPhotoFallback');
    const reasonValue = document.getElementById('reasonValue');

    let scanStream = null;
    let scanInterval = null;

    const formatDate = (value) => {
        if (!value) return '—';
        return new Intl.DateTimeFormat('en-KE', {
            dateStyle: 'medium',
            timeStyle: 'short',
        }).format(new Date(value));
    };

    const buildCountdownText = (expiresAt) => {
        if (!expiresAt) return '';
        const expires = new Date(expiresAt).getTime();
        const now = Date.now();
        const diffMs = expires - now;

        const minutes = Math.floor(Math.abs(diffMs) / 60000);
        const hours = Math.floor(minutes / 60);
        const days = Math.floor(hours / 24);

        const formatDuration = () => {
            if (days > 0) return `${days}d ${hours % 24}h`;
            if (hours > 0) return `${hours}h ${minutes % 60}m`;
            return `${Math.max(minutes, 0)}m`;
        };

        if (diffMs <= 0) {
            return `Expired ${formatDuration()} ago`;
        }

        return `Expires in ${formatDuration()}`;
    };
    const extractToken = (raw) => {
        try {
            const parsed = new URL(raw);
            return parsed.pathname.split('/').pop() || raw;
        } catch {
            return raw;
        }
    };

    const setStatus = (found, message) => {
        resultCard.classList.remove('hidden');
        if (found) {
            statusDot.className = 'h-3 w-3 rounded-full bg-mint';
            statusText.textContent = 'Pass verified';
            statusValue.textContent = message;
            expiryCountdown.textContent = '';
        } else {
            statusDot.className = 'h-3 w-3 rounded-full bg-red-400';
            statusText.textContent = message;
            statusValue.textContent = 'Not found';
            holderName.textContent = '—';
            holderEmail.textContent = '—';
            passType.textContent = '—';
            passReference.textContent = '—';
            validFrom.textContent = '—';
            validUntil.textContent = '—';
            reasonValue.textContent = '—';
            holderPhoto.classList.add('hidden');
            holderPhotoFallback.classList.remove('hidden');
            expiryCountdown.textContent = '';
        }
    };

    const runLookup = async (token) => {
        if (!token) {
            setStatus(false, 'Provide a QR token first.');
            return;
        }

        lookupBtn.disabled = true;
        lookupBtn.textContent = 'Checking…';

        try {
            const response = await fetch('{{ route('security.lookup') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                body: JSON.stringify({ token }),
            });

            if (response.status === 404) {
                setStatus(false, 'No pass found for this token.');
                return;
            }

            const data = await response.json();

            statusDot.className = data.status === 'approved'
                ? 'h-3 w-3 rounded-full bg-mint'
                : data.status === 'pending'
                    ? 'h-3 w-3 rounded-full bg-amber'
                    : 'h-3 w-3 rounded-full bg-red-400';

            statusText.textContent = data.status === 'approved'
                ? 'Pass approved'
                : data.status === 'pending'
                    ? 'Pass pending review'
                    : 'Pass rejected / expired';

            statusValue.textContent = data.status ?? '—';
            holderName.textContent = data.holder_name ?? '—';
            holderEmail.textContent = data.holder_email ?? '—';
            passType.textContent = data.pass_type ?? '—';
            passReference.textContent = data.pass_reference ?? '—';
            reasonValue.textContent = data.reason ?? '—';
            validFrom.textContent = formatDate(data.valid_from);
            validUntil.textContent = formatDate(data.valid_until);
            expiryCountdown.textContent = buildCountdownText(data.valid_until);

            if (data.holder_photo_url) {
                holderPhoto.src = data.holder_photo_url;
                holderPhoto.classList.remove('hidden');
                holderPhotoFallback.classList.add('hidden');
            } else {
                holderPhoto.classList.add('hidden');
                holderPhotoFallback.classList.remove('hidden');
            }

            resultCard.classList.remove('hidden');
        } catch (error) {
            console.error(error);
            setStatus(false, 'Lookup failed. Try again.');
        } finally {
            lookupBtn.disabled = false;
            lookupBtn.textContent = 'Lookup';
        }
    };

    lookupBtn.addEventListener('click', () => runLookup(extractToken(tokenInput.value)));
    tokenInput.addEventListener('keydown', (e) => {
        if (e.key === 'Enter') runLookup(extractToken(tokenInput.value));
    });

    const stopCamera = () => {
        if (scanInterval) {
            clearInterval(scanInterval);
            scanInterval = null;
        }
        if (scanStream) {
            scanStream.getTracks().forEach(track => track.stop());
            scanStream = null;
        }
        scannerContainer.classList.add('hidden');
    };

    scanToggle.addEventListener('click', async () => {
        if (scanStream) {
            stopCamera();
            return;
        }

        try {
            scanStream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: 'environment' } });
            qrVideo.srcObject = scanStream;
            await qrVideo.play();
            scannerContainer.classList.remove('hidden');

            const track = scanStream.getVideoTracks()[0];
            const imageCapture = new ImageCapture(track);

            scanInterval = setInterval(async () => {
                try {
                    const bitmap = await imageCapture.grabFrame();
                    const canvas = document.createElement('canvas');
                    canvas.width = bitmap.width;
                    canvas.height = bitmap.height;
                    const ctx = canvas.getContext('2d');
                    ctx.drawImage(bitmap, 0, 0);
                    const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
                    const code = jsQR(imageData.data, imageData.width, imageData.height);
                    if (code?.data) {
                        tokenInput.value = extractToken(code.data);
                        stopCamera();
                        runLookup(tokenInput.value);
                    }
                } catch (error) {
                    console.error('scan error', error);
                }
            }, 500);
        } catch (error) {
            console.error(error);
            setStatus(false, 'Camera access denied.');
        }
    });

    stopScan.addEventListener('click', stopCamera);
</script>
<script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.js"></script>
@endsection
