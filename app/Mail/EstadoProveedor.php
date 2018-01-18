<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class EstadoProveedor extends Mailable
{
    use Queueable, SerializesModels;

    protected $estado;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($estado)
    {
        $this->estado = $estado;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
     public function build()
    {
        $address = 'crea-tu-ento@service.com';
        $name = 'Crea Tu Evento';
        $subject = 'Estado Proveedor';
        return $this->view('mails.estado',  ['estado' => $this->estado])
                ->from($address, $name)
                ->subject($subject);
    }
}
