<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $details; 

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($details, $tipo)
    {
        $this->details = $details; 
        $this->tipo = $tipo;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if ($this->tipo === 1)
        return $this->subject('Mail de test')->view('myMail');
        else return $this->subject('Mail de test2')->view('templateSolicitudRechazo');
    }
}
