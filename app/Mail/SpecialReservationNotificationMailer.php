<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

use App\Repositories\ReservationStatusRepository as ReservationStatus;

class SpecialReservationNotificationMailer extends Mailable
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
            case ReservationStatus::accepted(): 
                return $this->subject('RESERVA ESPECIAL ACEPTADA')
                    ->view($dir.'specialCancel');
            case ReservationStatus::rejected(): 
                return $this->subject('RESERVA ESPECIAL RECHAZADA')
                    ->view($dir.'specialCancel');
            default: 
                return $this->subject('RESERVA ESPECIAL CANCELADA')
                    ->view($dir.'specialCancel');
        }
    }
}
