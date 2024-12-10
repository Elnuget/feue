<?php

namespace App\Mail;

use App\Models\Matricula;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MatriculaAprobada extends Mailable
{
    use Queueable, SerializesModels;

    public $matricula;

    public function __construct(Matricula $matricula)
    {
        $this->matricula = $matricula;
    }

    public function build()
    {
        return $this->subject('¡Felicidades! Matrícula Aprobada')
                    ->view('emails.matricula_aprobada');
    }
}