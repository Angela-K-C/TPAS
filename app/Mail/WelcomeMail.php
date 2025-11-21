<?php

namespace App\Mail;

use App\Models\TemporaryPass;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class WelcomeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $userName;
    public $status;
    public ?TemporaryPass $temporaryPass;

    /**
     * Create a new message instance.
     */
    public function __construct($userName, $status, ?TemporaryPass $temporaryPass = null)
    {
        $this->userName = $userName;
        $this->status = $status;
        $this->temporaryPass = $temporaryPass;
    }

    /**
     * Get the message envelope [Header information for emailW]
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Temporary Pass Application $this->status",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.status',
            with: [
                'userName' => $this->userName,
                'status'   => $this->status,
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        if ($this->status !== 'approved' || ! $this->temporaryPass) {
            return [];
        }

        $pass = $this->temporaryPass;
        $pass->ensureQrCodeAssets();

        if (! $pass->qr_code_path || ! Storage::disk('public')->exists($pass->qr_code_path)) {
            return [];
        }

        $svg = Storage::disk('public')->get($pass->qr_code_path);
        $qrDataUri = 'data:image/svg+xml;base64,' . base64_encode($svg);
        $reference = strtoupper(substr($pass->qr_code_token ?? (string) $pass->id, 0, 8));

        $pdf = Pdf::loadView('passes.qr-pdf', [
            'pass' => $pass,
            'qrDataUri' => $qrDataUri,
            'reference' => $reference,
        ]);

        return [
            Attachment::fromData(
                fn () => $pdf->output(),
                "temporary-pass-{$pass->id}.pdf"
            )->withMime('application/pdf'),
        ];
    }
}
