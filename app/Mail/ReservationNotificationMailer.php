<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

use App\Repositories\ReservationStatusRepository as ReservationStatus;

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
            case ReservationStatus::pending():
                return $this->subject('SOLICITUD DE RESERVA ENVIADA')
                    ->view($dir.'create');
            case ReservationStatus::accepted(): 
                return $this->subject('SOLICITUD DE RESERVA ACEPTADA')
                    ->view($dir.'accept');
            case ReservationStatus::rejected(): 
                return $this->subject('SOLICITUD DE RESERVA RECHAZADA')
                    ->view($dir.'reject');
            case ReservationStatus::cancelled(): 
                return $this->subject('SOLICITUD DE RESERVA CANCELADA')
                    ->view($dir.'cancel');
            default: 
                return $this->subject('SOLICITUD DE RESERVA CANCELADA')
                    ->view($dir.'cancel');
        }
    }
}
