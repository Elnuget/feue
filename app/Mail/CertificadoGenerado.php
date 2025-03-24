<?php

namespace App\Mail;

use App\Models\Certificado;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CertificadoGenerado extends Mailable
{
    use Queueable, SerializesModels;

    public $certificado;

    public function __construct(Certificado $certificado)
    {
        $this->certificado = $certificado;
    }

    public function build()
    {
        return $this->subject('¡Felicidades! Tu Certificado está Listo')
                    ->view('emails.certificado_generado');
    }
} 