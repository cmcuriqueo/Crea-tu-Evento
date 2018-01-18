<?php

namespace App\Mail;

use App\User;
use App\Proveedor;
use App\Rol;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class NewProveedorToOperador extends Mailable
{
    use Queueable, SerializesModels;

    protected $operador;
    protected $proveedor;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $operador, Proveedor $proveedor)
    {
        $this->operador = $operador;
        $this->proveedor = $proveedor;
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
        $subject = 'Nuevo proveedor registrado';
        return $this->view('mails.new-proveedor-to-operador',  
            ['operador' => $this->operador, 'proveedor' => $this->proveedor])
                ->from($address, $name)
                ->subject($subject);
    }
}
