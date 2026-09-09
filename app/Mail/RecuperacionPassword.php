<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RecuperacionPassword extends Mailable
{
    use Queueable, SerializesModels;

    public string $nombre;
    public string $passwordTemporal;

    public function __construct(
        string $nombre,
        string $passwordTemporal
    ) {
        $this->nombre = $nombre;
        $this->passwordTemporal = $passwordTemporal;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Recuperación de contraseña - VITALHOME',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.recuperacion-password',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
