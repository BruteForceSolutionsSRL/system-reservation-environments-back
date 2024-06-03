<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReservationNotificationMailer extends Mailable
{
    use Queueable, SerializesModels;

    public $details; 
    private $type;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($details, $type)
    {
        $this->details = $details; 
        $this->type = $type; 
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $dir = 'mail/templates/reservation/';
        switch ($this->type) {
            case 1:
                return $this->subject('SOLICITUD DE RESERVA ENVIADA')
                    ->view($dir.'create');
            case 2: 
                return $this->subject('SOLICITUD DE RESERVA ACEPTADA')
                    ->view($dir.'accept');
            case 3: 
                return $this->subject('SOLICITUD DE RESERVA RECHAZADA')
                    ->view($dir.'reject');
            default: 
                return $this->subject('SOLICITUD DE RESERVA CANCELADA')
                    ->view($dir.'cancel');
        }
    }
}
