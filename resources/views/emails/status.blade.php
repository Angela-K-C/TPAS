<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Temporary Pass Application Status</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #f5f0ff;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            color: #1f2933;
        }

        .email-wrapper {
            width: 100%;
            background-color: #f5f0ff;
            padding: 24px 0;
        }

        .email-container {
            max-width: 560px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 16px;
            box-shadow: 0 12px 30px rgba(88, 28, 135, 0.16);
            overflow: hidden;
            border: 1px solid rgba(129, 140, 248, 0.25);
        }

        .email-header {
            padding: 20px 24px;
            background: radial-gradient(circle at top left, #e9d5ff, #c4b5fd, #ede9fe);
            border-bottom: 1px solid rgba(129, 140, 248, 0.3);
            text-align: left;
        }

        .email-title {
            margin: 0;
            font-size: 22px;
            font-weight: 700;
            letter-spacing: 0.02em;
            color: #4c1d95;
        }

        .email-subtitle {
            margin: 4px 0 0;
            font-size: 13px;
            color: #4b5563;
        }

        .email-body {
            padding: 24px 24px 8px;
        }

        .greeting {
            margin: 0 0 16px;
            font-size: 16px;
            font-weight: 600;
            color: #111827;
        }

        .status-pill {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            margin-bottom: 12px;
        }

        .status-pill--approved {
            background-color: #ecfdf3;
            color: #15803d;
            border: 1px solid rgba(22, 163, 74, 0.25);
        }

        .status-pill--rejected {
            background-color: #fef2f2;
            color: #b91c1c;
            border: 1px solid rgba(239, 68, 68, 0.25);
        }

        .primary-text {
            margin: 0 0 8px;
            font-size: 15px;
            line-height: 1.6;
            color: #374151;
        }

        .secondary-text {
            margin: 0 0 16px;
            font-size: 13px;
            line-height: 1.6;
            color: #6b7280;
        }

        .highlight-card {
            margin: 16px 0 20px;
            padding: 12px 14px;
            border-radius: 12px;
            background: linear-gradient(135deg, #ede9fe, #f5f3ff);
            border: 1px solid rgba(129, 140, 248, 0.35);
        }

        .highlight-title {
            margin: 0 0 4px;
            font-size: 13px;
            font-weight: 700;
            color: #4c1d95;
        }

        .highlight-text {
            margin: 0;
            font-size: 12px;
            line-height: 1.5;
            color: #4b5563;
        }

        .note {
            margin: 0 0 12px;
            font-size: 12px;
            line-height: 1.5;
            color: #6b7280;
        }

        .divider {
            border: 0;
            border-top: 1px solid #e5e7eb;
            margin: 16px 0;
        }

        .email-footer {
            padding: 12px 24px 20px;
            font-size: 11px;
            color: #9ca3af;
            background-color: #f9fafb;
            border-top: 1px solid #e5e7eb;
        }

        .brand {
            font-weight: 600;
            color: #4c1d95;
        }

        @media (max-width: 600px) {
            .email-container {
                margin: 0 12px;
                border-radius: 14px;
            }

            .email-header,
            .email-body,
            .email-footer {
                padding-left: 18px;
                padding-right: 18px;
            }
        }
    </style>
</head>
<body>
<div class="email-wrapper">
    <div class="email-container">
        <div class="email-header">
            <p class="email-title">Temporary Pass Status</p>
            <p class="email-subtitle">TPAS &mdash; Temporary Pass Application System</p>
        </div>

        <div class="email-body">
            <p class="greeting">Hello, {{ $userName }}!</p>

            @if($status === "approved")
                <span class="status-pill status-pill--approved">Approved</span>
                <p class="primary-text">
                    Great news! Your temporary pass application has been <strong>approved</strong> 🤗.
                </p>
                <div class="highlight-card">
                    <p class="highlight-title">Your QR code is attached</p>
                    <p class="highlight-text">
                        We have attached your QR code as a PDF. Please save it and present it at the gate when requested.
                    </p>
                </div>
                <p class="secondary-text">
                    For your security, do not share this QR code publicly. If you suspect any issue, please contact the security office.
                </p>
            @else
                <span class="status-pill status-pill--rejected">Rejected</span>
                <p class="primary-text">
                    We’re sorry, but your temporary pass application has been <strong>rejected</strong> ❌.
                </p>
                <div class="highlight-card">
                    <p class="highlight-title">Next steps</p>
                    <p class="highlight-text">
                        Please visit the security office to apply physically for a temporary pass or to get more information about this decision.
                    </p>
                </div>
                <p class="secondary-text">
                    You can always submit a new application if your circumstances change or if requested by security personnel.
                </p>
            @endif

            <p class="note">
                This is an automated message regarding your temporary pass application.
            </p>

            <hr class="divider">

            <p class="secondary-text" style="margin-bottom: 0;">
                Thank you for using <span class="brand">TPAS</span>.
            </p>
        </div>

        <div class="email-footer">
            <p style="margin: 0 0 4px;">Please do not reply to this email.</p>
            <p style="margin: 0;">&copy; {{ date('Y') }} <span class="brand">TPAS</span>. All rights reserved.</p>
        </div>
    </div>
</div>
</body>
</html>
