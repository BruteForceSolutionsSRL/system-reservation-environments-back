<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

use App\Repositories\ClassroomRepository;

class ClassroomNotificationMailer extends Mailable
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
        $dir = 'mail/templates/classroom';
        switch ($this->type) {
            case 1:
                return $this->subject('CREACION DE AMBIENTE')
                    ->view($dir);
            case 2: 
                return $this->subject('ELIMINACION DE AMBIENTE')
                    ->view($dir);
            default:
                return $this->subject('ACTUALIZACION DE DATOS DE AMBIENTE')
                    ->view($dir);
        }
    }
}
