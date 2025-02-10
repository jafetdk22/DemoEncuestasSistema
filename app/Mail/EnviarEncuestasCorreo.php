<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EnviarEncuestasCorreo extends Mailable
{
    use Queueable, SerializesModels;
    protected $orden, $encuesta;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($orden, $encuesta,$conexion)
    {
        $this ->orden=$orden;
        $this ->encuesta=$encuesta;
        $this ->conexion=$conexion;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->view('correos.enviar_lerma')
            ->subject("Notificación Encuesta Disponible")
            ->with([
                'orden' => $this->orden,
                'encuesta' => $this->encuesta,
                'conexion' => $this->conexion,
            ]);
    }
}
