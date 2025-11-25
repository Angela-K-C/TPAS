<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Temporary Pass QR</title>
    <style>
        body { font-family: Helvetica, Arial, sans-serif; margin: 24px; color: #0f172a; }
        .card { border: 1px solid #e2e8f0; border-radius: 16px; padding: 24px; }
        .header { display: flex; align-items: center; gap: 18px; margin-bottom: 18px; }
        .logo { height: 110px; }
        .heading { font-size: 18px; margin-bottom: 4px; }
        .subheading { font-size: 12px; color: #475569; margin-top: 0; }
        .qr { margin-top: 16px; display: flex; justify-content: center; }
        .qr img { width: 240px; height: 240px; padding: 12px; border: 1px solid #e2e8f0; border-radius: 12px; background: #fff; }
        .meta { margin-top: 12px; text-align: center; font-size: 12px; color: #475569; }
    </style>
</head>
<body>
    <div class="card">
        <div class="header">
            <img src="{{ public_path('logo.png') }}" alt="TPAS logo" class="logo">
            <div class="heading">Temporary Pass QR</div>
        </div>
        <p class="subheading">Present this code for verification. Reference: {{ $reference }}</p>
        <div class="qr">
            <img src="{{ $qrDataUri }}" alt="Temporary pass QR code">
        </div>
        <div class="meta">
            {{ $pass->visitor_name ?? 'Pass Holder' }} • Valid from {{ optional($pass->valid_from)?->format('M d, Y') ?? '—' }} to {{ optional($pass->valid_until)?->format('M d, Y') ?? '—' }}
        </div>
    </div>
</body>
</html>
