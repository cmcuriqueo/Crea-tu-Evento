<?php

namespace App\Mail;

use App\Reserva as ModelReserva;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ResponseProveedor extends Mailable
{
    use Queueable, SerializesModels;

    protected $reserva;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(ModelReserva $reserva)
    {
        $this->reserva = $reserva;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $address = 'crea-tu-evento@service.com';
        $name = 'Crea Tu Evento';
        $subject = 'Respuesta a presupuesto';
        return $this->view('mails.new-response-proveedor',  ['reserva' => $this->reserva])
                ->from($address, $name)
                ->subject($subject);
    }
}
