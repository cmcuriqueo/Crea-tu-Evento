<?php

namespace App\Mail;
use App\User;
use App\Proveedor;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class NewProveedorToSupervisor extends Mailable
{
    use Queueable, SerializesModels;

    protected $supervisor;
    protected $proveedor;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $supervisor, Proveedor $proveedor)
    {
        $this->supervisor = $supervisor;
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
        return $this->view('mails.new-proveedor-to-supervisor',  
            ['supervisor' => $this->supervisor, 'proveedor' => $this->proveedor])
                ->from($address, $name)
                ->subject($subject);
    }
}
