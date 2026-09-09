<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class UsuarioRegistrado extends Mailable
{
    use Queueable, SerializesModels;

    public string $nombre;
    public string $email;
    public string $passwordTemporal;

    /**
     * Crear una nueva instancia del mensaje.
     */
    public function __construct(
        string $nombre,
        string $email,
        string $passwordTemporal
    ) {
        $this->nombre = $nombre;
        $this->email = $email;
        $this->passwordTemporal = $passwordTemporal;
    }

    /**
     * Asunto del correo.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Bienvenido a VITALHOME - Datos de acceso',
        );
    }

    /**
     * Contenido del correo.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.usuario-registrado',
        );
    }

    /**
     * Archivos adjuntos.
     */
    public function attachments(): array
    {
        return [];
    }
}
