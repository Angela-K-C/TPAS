@extends('layouts.app', ['title' => 'TPAS Security Desk · Verify Pass', 'showFooter' => false])

@section('content')
<section class="min-h-screen bg-gradient-to-br from-[#02050d] via-[#070f1f] to-[#151538] py-12 text-white">
    <div class="max-w-6xl mx-auto px-4 space-y-8">
        <header class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <p class="text-xs uppercase tracking-[0.35em] text-mint/70">Security Portal</p>
                <h1 class="mt-2 text-3xl font-semibold">Scan or enter a QR token</h1>
                <p class="mt-2 text-sm text-white/60 max-w-xl">
                    TPAS syncs every guard station. Paste the token printed under the QR code or launch the live scanner.
                </p>
            </div>
            <form action="{{ route('security.logout') }}" method="POST">
                @csrf
                <button type="submit"
                        class="inline-flex items-center gap-2 rounded-2xl border border-white/15 bg-white/10 px-5 py-3 text-sm font-semibold text-white shadow-sm hover:border-mint hover:text-mint focus:ring-2 focus:ring-mint/30">
                    Log out
                </button>
            </form>
        </header>

        <div class="grid gap-6 lg:grid-cols-5">
            <section class="lg:col-span-3 rounded-3xl border border-white/10 bg-[#0c1222]/80 backdrop-blur p-6 shadow-[0_30px_90px_rgba(0,0,0,0.65)] space-y-4">
                <div class="flex items-center justify-between flex-wrap gap-2">
                    <div>
                        <p class="text-xs uppercase tracking-[0.35em] text-mint/70">Lookup Console</p>
                        <h2 class="mt-2 text-xl font-semibold">Token search</h2>
                    </div>
                    <span class="inline-flex items-center gap-2 rounded-full bg-mint/15 px-3 py-1 text-xs font-semibold text-white">
                        <span class="h-2 w-2 rounded-full bg-mint animate-pulse shadow-[0_0_8px_rgba(82,224,196,0.7)]"></span>
                        Live AMS feed
                    </span>
                </div>
                <label for="token" class="text-sm font-medium text-white/80">QR token</label>
                <div class="flex flex-col gap-3 sm:flex-row">
                    <input type="text" id="token" name="token" placeholder="Paste or scan token..."
                           class="flex-1 rounded-2xl border border-white/15 bg-[#0f172a] px-4 py-3 text-base text-white placeholder:text-white/40 focus:border-white/40 focus:ring-2 focus:ring-white/40 transition" />
                    <button id="lookupBtn"
                            class="rounded-2xl border border-white/20 bg-white/10 px-5 py-3 font-semibold text-white shadow-[0_20px_40px_rgba(0,0,0,0.35)] hover:bg-white/20 hover:border-white/40 focus:ring-2 focus:ring-white/30">
                        Lookup
                    </button>
                    <button id="scanToggle"
                            class="rounded-2xl border border-white/20 px-5 py-3 font-semibold text-white/80 hover:border-white/40 hover:text-white focus:ring-2 focus:ring-white/30">
                        Scan QR
                    </button>
                </div>
                <div id="scannerContainer" class="hidden rounded-2xl border border-white/10 bg-white/5 p-4 space-y-3">
                    <video id="qrVideo" class="w-full rounded-xl bg-black/60" playsinline></video>
                    <p class="text-xs text-white/60">Point the device camera at the QR label. We’ll auto-fill the token.</p>
                    <button id="stopScan" class="inline-flex items-center gap-2 rounded-xl bg-white/10 px-4 py-2 text-sm font-semibold text-white hover:bg-white/20">
                        Stop scanning
                    </button>
                </div>
                
            </section>

            <section id="resultCard" class="lg:col-span-2 hidden rounded-3xl border border-white/10 bg-[#080c1a]/80 backdrop-blur p-6 shadow-[0_30px_90px_rgba(0,0,0,0.55)]">
                <div id="resultHeader" class="flex items-center gap-3">
                    <div id="statusDot" class="h-3 w-3 rounded-full bg-white/30"></div>
                    <p id="statusText" class="text-lg font-semibold">Awaiting lookup…</p>
                </div>
                <dl class="mt-6 grid gap-4 text-sm text-white/80">
                    <div class="flex gap-4 items-center">
                        <div class="relative h-24 w-24 overflow-hidden rounded-2xl border border-white/10 bg-white/5">
                            <img id="holderPhoto" class="hidden h-full w-full object-cover" alt="Pass holder photo">
                            <div id="holderPhotoFallback" class="flex h-full w-full items-center justify-center text-[0.65rem] font-semibold uppercase tracking-wide text-white/50">
                                No photo
                            </div>
                        </div>
                        <div>
                            <dt class="text-xs uppercase tracking-wide text-white/50">Holder</dt>
                            <dd class="mt-2 text-base font-semibold text-white" id="holderName">—</dd>
                            <p class="text-sm text-white/60" id="holderEmail">—</p>
                        </div>
                    </div>
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <dt class="text-xs uppercase tracking-wide text-white/50">Pass reference</dt>
                            <dd class="mt-2 font-semibold text-white" id="passReference">—</dd>
                        </div>
                        <div>
                            <dt class="text-xs uppercase tracking-wide text-white/50">Pass type</dt>
                            <dd class="mt-2 font-semibold text-white" id="passType">—</dd>
                        </div>
                        <div>
                            <dt class="text-xs uppercase tracking-wide text-white/50">Status</dt>
                            <dd class="mt-2 font-semibold text-white" id="statusValue">—</dd>
                        </div>
                        <div>
                            <dt class="text-xs uppercase tracking-wide text-white/50">Reason</dt>
                            <dd class="mt-2 font-semibold text-white" id="reasonValue">—</dd>
                        </div>
                        <div>
                            <dt class="text-xs uppercase tracking-wide text-white/50">Valid from</dt>
                            <dd class="mt-2 text-white" id="validFrom">—</dd>
                        </div>
                        <div>
                            <dt class="text-xs uppercase tracking-wide text-white/50">Valid until</dt>
                            <dd class="mt-2 text-white" id="validUntil">—</dd>
                        </div>
                    </div>
                </dl>
            </section>
        </div>
    </div>
</section>

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
