<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use App\Reserva as ModelReserva;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class CancelarReservasDeProveedor extends Mailable
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
        $subject = 'Reserva cancelada';
        return $this->view('mails.cancelar-reserva-proveedor',  ['reserva' => $this->reserva])
                ->from($address, $name)
                ->subject($subject);
    }
}
